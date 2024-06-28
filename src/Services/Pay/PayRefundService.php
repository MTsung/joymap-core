<?php

namespace Mtsung\JoymapCore\Services\Pay;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Facades\Payment\JoyPay;
use Mtsung\JoymapCore\Models\AdminUser;
use Mtsung\JoymapCore\Models\CoinLog;
use Mtsung\JoymapCore\Models\CouponNumber;
use Mtsung\JoymapCore\Models\CouponNumberTransactionLog;
use Mtsung\JoymapCore\Models\CreditCardLog;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Models\StoreWalletTransactionRecord;
use Mtsung\JoymapCore\Services\Jcoin\AddJcoinService;
use Mtsung\JoymapCore\Services\Jcoin\SubJcoinService;
use Mtsung\JoymapCore\Services\StoreWallet\StoreWalletTransactionService;
use Throwable;


/**
 * @method static bool run(PayLog $payLog)
 */
class PayRefundService
{
    use AsObject;

    protected PayLog $payLog;

    protected Store $store;

    protected ?CreditCardLog $creditCardLog = null;

    protected Member $member;

    protected mixed $log;

    public function __construct()
    {
        $this->log = Log::stack([
            config('logging.default'),
            'refund',
        ]);
    }

    /**
     * @throws Exception
     */
    public function handle(PayLog $payLog): bool
    {
        $this->log->info(__METHOD__ . ': start refund', [$payLog]);

        $this->payLog = $payLog;

        $this->store = $payLog->store;

        $this->member = $payLog->member;

        $this->creditCardLog = $payLog->memberCreditCardLog->first();

        return DB::transaction(function () {
            $this->validate();
            $this->log->info('validate pass', [$this->payLog->id]);

            $this->creditCardRefund();
            $this->log->info('creditCardRefund pass', [$this->payLog->id]);

            $this->jcoinRefund();
            $this->log->info('jcoinRefund pass', [$this->payLog->id]);


            if ($this->payLog->discount_type == PayLog::DISCOUNT_TYPE_COUPON) {
                $this->couponRefund();
                $this->log->info('couponRefund pass', [$this->payLog->id]);
            }

            if ($this->payLog->company_give_store_amount > 0) {
                $this->storeWalletRefund();
                $this->log->info('storeWalletRefund pass', [$this->payLog->id]);
            }

            if (!$this->successRefund()) {
                $this->log->error('successRefund error', [$this->payLog->id]);

                return false;
            }
            $this->log->info('successRefund pass', [$this->payLog->id]);

            return true;
        });
    }

    /**
     * 檢查支付能不能退刷
     * @throws Exception
     */
    private function validate(): void
    {
        // 檢查此筆訂單是否已退款
        if ($this->payLog->refund_at) {
            throw new Exception('已退款過', 422);
        }

        // 驗證支付記錄的時間是否超過一天, 如果超過就無法申請此退款流程
        if ($this->payLog->created_at->diffInDays(Carbon::now()) > 1) {
            throw new Exception('該筆支付記錄已超過一天，無法退款', 422);
        }

        //--------- 以下是有刷卡才需要的判斷，有新判斷不要寫在這下面
        if(!$this->creditCardLog) return;

        // 查詢藍新這筆訂單的狀態
        if (!$queryRes = $this->getJoyPay()->query()) {
            throw new Exception('金流端伺服器異常，請稍後再試', 422);
        }

        $this->log->info(__METHOD__ . ': queryRes', [$queryRes]);

        // 藍新查無交易資料
        if ($queryRes['Status'] === 'TRA10021') {
            throw new Exception('金流查無交易資料', 422);
        }

        // 查詢失敗
        if ($queryRes['Status'] !== 'SUCCESS') {
            throw new Exception('金流訂單資料有誤', 422);
        }

        // 不是 1 的話代表不是付款成功
        if ((int)$queryRes['Result']['TradeStatus'] !== 1) {
            throw new Exception('該筆交易非成功單號', 422);
        }
        //--------- 以上是有刷卡才需要的判斷，有新判斷不要寫在這下面
    }

    private function getJoyPay(): \Mtsung\JoymapCore\Helpers\Payment\JoyPay
    {
        return JoyPay::bySpGateway()
            ->store($this->store)
            ->orderNo($this->creditCardLog->credit_no)
            ->money($this->creditCardLog->amount);
    }

    /**
     * 信用卡退刷
     * @throws Exception
     * @throws Throwable
     */
    private function creditCardRefund(): void
    {
        if (!$this->creditCardLog) return;

        if (!$cancelRes = $this->getJoyPay()->cancel()) {
            throw new Exception('金流端伺服器異常，請稍後再試', 422);
        }

        $this->log->info(__METHOD__ . ': cancelRes', [$cancelRes]);

        // 請款中就打退款 API
        if (isset($resCancel['Status']) && $resCancel['Status'] === 'TRA10048') {
            if (!$cancelRes = $this->getJoyPay()->close()) {
                throw new Exception('金流端伺服器異常，請稍後再試', 422);
            }

            $this->log->info(__METHOD__ . ': cancelRes', [$cancelRes]);
        }

        // 退刷不成功, 觸發 exception
        if ($cancelRes['Status'] !== 'SUCCESS') {
            throw new Exception('退刷失敗：' . ($cancelRes['Message'] ?? 'Error'), 422);
        }

        $this->createCancelLog($cancelRes);
    }

    /**
     * @throws Throwable
     */
    private function createCancelLog(array $cancelRes): void
    {
        $this->creditCardLog->replicate()
            ->fill([
                'status' => 1,
                'amount' => -(int)$this->creditCardLog->amount,
                'give_store_amount' => -(int)$this->creditCardLog->give_store_amount,
                'traded_at' => Carbon::now(),
                'ret_code' => $cancelRes['Status'],
                'response_log' => json_encode($cancelRes),
                'is_charge' => (int)!$this->creditCardLog->is_charge,
            ])
            ->saveOrFail();

        // 如果原本的信用卡記錄尚未被抽成,那新的刷退負數信用卡記錄就要記錄為已被抽成, 且原本的記錄也要改成已被抽成
        // (每半小時執行的排程會把負數的記錄款項退回給店家)
        if (!$this->creditCardLog->is_charge) {
            $this->creditCardLog->is_charge = 1;
            $this->creditCardLog->saveOrFail();
        }
    }

    /**
     * 享樂幣回收/回補
     * @throws Exception
     */
    private function jcoinRefund(): void
    {
        $coinLogs = $this->payLog->coinLog()->whereNotIn('type', CoinLog::TYPE_CHANGES_BY_ADMIN)->get();

        foreach ($coinLogs as $coinLog) {
            if ((int)$coinLog->coin > 0) {
                SubJcoinService::make()
                    ->payLog($this->payLog)
                    ->store($this->store)
                    ->handle(
                        $this->member,
                        '系統回收:' . $this->payLog->pay_no,
                        CoinLog::TYPE_SYSTEM_RECLAIM,
                        $coinLog->coin,
                    );
            } else {
                AddJcoinService::make()
                    ->payLog($this->payLog)
                    ->store($this->store)
                    ->handle(
                        $this->member,
                        '系統回補:' . $this->payLog->pay_no,
                        CoinLog::TYPE_SYSTEM_COMPENSATION,
                        -$coinLog->coin,
                        Carbon::now()->addDays(90)->startOfDay(),
                    );
            }
        }
    }

    /**
     * 天使折價券回補
     * @return void
     */
    private function couponRefund(): void
    {
        if (!$dealer = $this->member->dealer()) {
            Log::error('dealRefundWithCoupon: 有用天使折價券卻沒天使身份');
            return;
        }

        $couponNumberTransactionLogs = $this->payLog->couponNumberTransactionLogs();
        foreach ($couponNumberTransactionLogs as $couponNumberTransactionLog) {
            // 非核銷的話就不是要回補的資料
            if ($couponNumberTransactionLog->action != CouponNumberTransactionLog::ACTION_REDEEM) {
                continue;
            }

            // 可用卷數補回
            $dealer->increment('balance_coupon_count');

            $couponNumberTransactionLog->couponNumber->update([
                'status' => CouponNumber::STATUS_AVAILABLE,
            ]);

            $couponNumberTransactionLog->replicate()
                ->fill([
                    'action' => CouponNumberTransactionLog::ACTION_UNDO_REDEMPTION,
                    'comment' => '退款回補折價券',
                    'created_at' => Carbon::now()
                ])
                ->saveOrFail();
        }
    }

    /**
     * 店家儲值金異動
     * @return void
     * @throws Exception
     */
    private function storeWalletRefund(): void
    {
        $transactionService = StoreWalletTransactionService::make()->payLog($this->payLog);

        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof Member) {
                $transactionService->member($user);
            }

            if ($user instanceof StoreUser) {
                $transactionService->storeUser($user);
            }

            if ($user instanceof AdminUser) {
                $transactionService->adminUser($user);
            }

        }

        // 應扣儲值金 = 折抵金額
        $amount = -(int)$this->payLog->user_use_coin;
        $type = StoreWalletTransactionRecord::TYPE_REFUND;
        $transactionService->handle($this->store, $type, $amount);

        // 應退平台費 = 折抵金額 - 拿到的儲值金
        $amount = (int)$this->payLog->user_use_coin - (int)$this->payLog->company_give_store_amount;
        $type = StoreWalletTransactionRecord::TYPE_PLATFORM_COMMISSION;
        $transactionService->handle($this->store, $type, $amount);
    }

    private function successRefund(): bool
    {
        return $this->payLog->update([
            'user_pay_status' => PayLog::USER_PAY_STATUS_REFUND,
            'company_pay_status' => PayLog::COMPANY_PAY_STATUS_REFUND,
            'refund_at' => Carbon::now()
        ]);
    }
}
