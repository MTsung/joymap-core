<?php

namespace Mtsung\JoymapCore\Services\Subscription;

use Mtsung\JoymapCore\Repositories\Store\StorePayLogsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Mtsung\JoymapCore\Facades\Payment\JoyPay;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreCreditcardLogs;
use Mtsung\JoymapCore\Models\StorePayLogs;
use Mtsung\JoymapCore\Models\StorePlan;
use Mtsung\JoymapCore\Models\StoreSubscription;
use Mtsung\JoymapCore\Models\StoreSubscriptionPeriod;
use Throwable;

/**
 * @method static self run(Store $store, StorePlan $storePlan, int $instFlag, int $count = 1, bool $isGetAmount = false)
 * @method static self make()
 */
class StorePlanPayService
{
    use AsObject;

    public int $code = 0;
    public string $msg = '失敗';
    public string $html = '';

    public StoreSubscription $storeSubscription;
    public StorePayLogs $storePayLogs;
    public StoreCreditcardLogs $storeCreditcardLogs;
    public ?StoreSubscriptionPeriod $storeSubscriptionPeriod;
    // 目前的週期
    public ?StoreSubscriptionPeriod $nowPeriod;
    // 最後的週期
    public ?StoreSubscriptionPeriod $lastPeriod;

    public StorePlan $storePlan;

    public ?Store $store = null;

    public string $payNo;

    public int $originalAmount = 0;
    public int $amount = 0;
    public int $instFlag = 1;
    public int $count = 1;


    public function __construct(
        private StorePayLogsRepository $storePayLogsRepository,
    )
    {
    }

    public function handle(Store $store, StorePlan $storePlan, int $instFlag, int $count = 1, bool $isGetAmount = false): self
    {
        if (!isset($store->storeSubscription)) {
            throw new Exception('加值服務功能尚未開啟', 422);
        }

        if (!$isGetAmount && !isset($store->storeCreditCard)) {
            throw new Exception('尚未綁定信用卡，無法付款', 422);
        }

        $this->payNo = Rand::storePayNo();
        $this->storeSubscription = $store->storeSubscription;
        $this->store = $store;
        $this->storePlan = $storePlan;
        $this->instFlag = $instFlag;
        $this->count = $count;

        $this->lastPeriod = $this->store->storeSubscriptionPeriod()
            ->where('status', 1)
            ->where('type', $this->storePlan->type)
            ->orderByDesc('period_end_at')
            ->first();

        $this->nowPeriod = $this->store->nowStoreSubscriptionPeriod()->first();

        $this->setAmount();

        if ($isGetAmount) return $this;

        $this->storePayLogs = $this->createDefaultPayLog();

        $this->storeSubscriptionPeriod = $this->createDefaultStoreSubscriptionPeriod();

        if (!$this->swipeStoreCard()) {
            return $this;
        }

        $this->success();

        return $this;
    }

    public function setAmount(): void
    {
        // 升級不用計算
        if ($this->storePlan->type == StorePlan::TYPE_SUBSCRIPTION) {
            if (!$this->isRenew()) {
                $this->count = 0;
            }
        }

        // 加價百分比
        $addPer = match ($this->instFlag) {
            3 => $this->storePlan->inst_rate3,
            6 => $this->storePlan->inst_rate6,
            12 => $this->storePlan->inst_rate12,
            default => 0,
        };

        $diffMoney = 0;
        if ($this->storePlan->type == StorePlan::TYPE_SUBSCRIPTION && !$this->isRenew()) {
            $periods = $this->store->storeSubscriptionPeriod()
                ->where('status', 1)
                ->where('type', $this->storePlan->type)
                ->where('period_end_at', '>', Carbon::now())
                ->get();

            // 加送的天數
            $addDays = $periods->sum('add_month') * 30;
            // 剩餘天數
            $diffInDays = Carbon::parse($periods->max('period_end_at'))->diffInDays(Carbon::now());
            // 這段總天數
            $allInDays = Carbon::parse($periods->max('period_end_at'))->diffInDays($periods->min('period_start_at'));

            // 每天差價多少
            $moneyInDay = ($this->storePlan->promotion_price - $this->nowPeriod->storePlan->promotion_price) / 365;

            // 用剩餘天數去看是否大於購買天數，如果大於就已購買的天數計算，小於則用實際剩餘天數計算
            $diffMoney = $moneyInDay * min($allInDays - $addDays, $diffInDays);
        }

        $this->originalAmount = round($this->storePlan->promotion_price * $this->count) + $diffMoney;

        $this->amount = round($this->originalAmount / ((100 - $addPer) / 100));
    }

    private function swipeStoreCard(): bool
    {
        $this->storeCreditcardLogs = $this->createDefaultCreditCardLog();

        /** @var Store $store */
        $store = Store::query()->find(env('SUBSCRIPTION_PAY_CARD_STORE_ID'));

        $res = JoyPay::bySpGateway()
            ->store($store)
            ->orderNo($this->payNo)
            ->money($this->amount)
            ->token($this->store->storeCreditCard->token)
            ->email($this->store->storeCreditCard->email)
            ->inst($this->instFlag == 1 ? 0 : $this->instFlag)
            ->isSubscription(true)
            ->pay();

        Log::info(__METHOD__ . ' res', [$res]);

        if ($res === false) {
            $this->msg = '連線失敗';
            $this->storePayLogs->update(['status' => StorePayLogs::STATUS_FAIL]);

            return false;
        }

        if ($res['Status'] === '3dVerify') {
            // 3D 驗證
            $this->code = 3;
            $this->msg = '必須進行 3D 驗證。';
            $this->html = $res['Result'];
            $this->storePayLogs->update(['status' => 4]);

            return false;
        }

        return $this->runRes($res);
    }

    private function success(): void
    {
        $this->code = 1;
        $this->msg = '成功';
    }

    private function runRes($res): bool
    {
        try {
            $status = $res['Status'] ?? '';
            $success = ($status === 'SUCCESS');
            $resResult = $res['Result'] ?? [];
            $this->msg = $res['Message'] ?? '無訊息';

            if (is_string($resResult)) {
                $resResult = json_decode($resResult, true);
            }

            $this->storePayLogs->update(['status' => $success ? StorePayLogs::STATUS_SUCCESS : StorePayLogs::STATUS_FAIL]);

            $this->storeCreditcardLogs->update([
                'bank_no' => $resResult['TradeNo'] ?? '',
                'store_pay_log_id' => $this->storePayLogs->id,
                'status' => (int)$success,
                'ret_code' => $status,
                'traded_at' => isset($resResult['AuthDate'], $resResult['AuthTime']) ?
                    Carbon::createFromFormat('YmdHis', $resResult['AuthDate'] . $resResult['AuthTime']) :
                    Carbon::now(),
                'response_log' => json_encode($res),
            ]);

            $this->storeSubscriptionPeriod?->update(['status' => (int)$success]);

            if ($success && $this->storePlan->type == StorePlan::TYPE_SUBSCRIPTION) {
                // 升級
                if (!$this->isRenew()) {
                    $now = Carbon::now();
                    $nowPeriod = $this->store->nowStoreSubscriptionPeriod()->first();
                    $periodEndAt = $nowPeriod->period_end_at;

                    // 把當下的結束
                    $this->store->nowStoreSubscriptionPeriod()
                        ->update(['period_end_at' => $now->copy()->subSecond()]);

                    // 新增一個到原本結束時間
                    $this->store->storeSubscriptionPeriod()->create([
                        'store_plan_id' => $this->storePlan->id,
                        'store_subscription_id' => $nowPeriod->store_subscription_id,
                        'store_pay_log_id' => $this->storePayLogs->id,
                        'add_month' => 0,
                        'is_renew' => 1,
                        'status' => 1,
                        'period_start_at' => $now,
                        'period_end_at' => $periodEndAt,
                    ]);

                    // 把未來的 period plan_id 改掉(因為可能之前按過續約)
                    $this->store->storeSubscriptionPeriod()
                        ->where('status', 1)
                        ->where('type', $this->storePlan->type)
                        ->where('period_end_at', '>', $now)
                        ->update(['store_plan_id' => $this->storePlan->id]);
                }
            }

            return $success;
        } catch (Throwable $e) {
            $this->storePayLogs->update(['status' => StorePayLogs::STATUS_FAIL]);

            Log::error(__METHOD__, [$e->getMessage(), $e]);
            $message = LineNotification::getMsgText($e, __METHOD__);
            event(new SendNotifyEvent($message));

            return false;
        }
    }

    private function createDefaultCreditCardLog(): StoreCreditcardLogs|Model
    {
        $createData = [
            'bank_no' => null,
            'credit_no' => $this->payNo,
            'credit_id' => $this->store->storeCreditCard->id,
            'credit_name' => $this->store->name,
            'status' => 0,
            'amount' => $this->amount,
            'card_4_num' => $this->store->storeCreditCard->card_4_num,
            'ret_code' => null,
            'traded_at' => null,
            'response_log' => null,
        ];

        return $this->storePayLogs->storeCreditcardLogs()->create($createData);
    }

    private function createDefaultPayLog(): StorePayLogs|Model
    {
        $storePayLogData = [
            'store_subscription_id' => $this->storeSubscription->id,
            'pay_no' => $this->payNo,
            'store_plan_id' => $this->storePlan->id,
            'buy_count' => $this->count,
            'inst_flag' => $this->instFlag,
            'original_amount' => $this->originalAmount,
            'amount' => $this->amount,
        ];

        return $this->storePayLogsRepository->create($storePayLogData);
    }

    private function createDefaultStoreSubscriptionPeriod(): StoreSubscriptionPeriod|Model|null
    {
        $addMonth = 0;
        if ($this->storePlan->type == StorePlan::TYPE_SUBSCRIPTION) {
            // 續約加送
            if ($this->isRenew()) {
                $addMonth = 3 * $this->count;
            } else {
                return null;
            }
        }

        $start = isset($this->lastPeriod) ? Carbon::parse($this->lastPeriod->period_end_at)->addDay()->startOfDay() : Carbon::today();

        $data = [
            'store_id' => $this->store->id,
            'store_plan_id' => $this->storePlan->id,
            'store_subscription_id' => $this->storeSubscription->id,
            'store_pay_log_id' => $this->storePayLogs->id,
            'period_start_at' => isset($this->storePlan->month) ? $start : null,
            'period_end_at' => isset($this->storePlan->month) ?
                $start->copy()->addMonths($this->storePlan->month * $this->count)
                    ->addMonths($addMonth)
                    ->subSecond() :
                null,
            'is_renew' => isset($this->storePlan->month) ? 1 : 0,
            'type' => $this->storePlan->type,
            'add_month' => $addMonth,
        ];

        return $this->store->storeSubscriptionPeriod()->create($data);
    }

    // 是否為續約
    private function isRenew(): bool
    {
        return $this->nowPeriod->store_plan_id == $this->storePlan->id;
    }
}
