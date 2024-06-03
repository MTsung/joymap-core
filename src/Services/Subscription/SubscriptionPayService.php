<?php

namespace Mtsung\JoymapCore\Services\Subscription;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Mtsung\JoymapCore\Facades\Payment\JoyPay;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\MemberCreditCard;
use Mtsung\JoymapCore\Models\MemberDealer;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\SubscriptionProgram;
use Mtsung\JoymapCore\Models\SubscriptionProgramCreditcardLog;
use Mtsung\JoymapCore\Models\SubscriptionProgramOrder;
use Mtsung\JoymapCore\Models\SubscriptionProgramPayLog;
use Mtsung\JoymapCore\Repositories\Member\MemberCreditCardRepository;
use Mtsung\JoymapCore\Repositories\Member\MemberDealerRepository;
use Mtsung\JoymapCore\Repositories\Subscription\SubscriptionProgramOrderRepository;
use Mtsung\JoymapCore\Repositories\Subscription\SubscriptionProgramPayLogRepository;
use Mtsung\JoymapCore\Services\Member\MemberDealerService;
use Mtsung\JoymapCore\Services\Member\MemberGradeService;
use Throwable;


/**
 * @method static bool run(Member $member, SubscriptionProgram $subscriptionProgram, Carbon $startAt, ?MemberDealer $fromInvite, bool $isFree)
 */
class SubscriptionPayService
{
    use AsObject;

    protected ?Store $store;

    protected Member $member;

    protected MemberCreditCard $memberCreditCard;

    protected SubscriptionProgram $subscriptionProgram;

    protected SubscriptionProgramPayLog $subscriptionProgramPayLog;

    protected SubscriptionProgramCreditcardLog $subscriptionProgramCreditcardLog;

    protected array $subscriptionProgramOrderIds;

    public string $creditNo = '';

    public bool $isFree = false;

    protected mixed $log;

    public function __construct(
        private SubscriptionProgramOrderService     $subscriptionProgramOrderService,
        private MemberDealerService                 $memberDealerService,
        private MemberGradeService                  $memberGradeService,
        private SubscriptionProgramPayLogRepository $subscriptionProgramPayLogRepository,
        private SubscriptionProgramOrderRepository  $subscriptionProgramOrderRepository,
        private MemberCreditCardRepository          $memberCreditCardRepository,
        private MemberDealerRepository              $memberDealerRepository,
    )
    {
        $this->log = Log::stack([
            config('logging.default'),
            'subscription',
        ]);
    }

    /**
     * @param Member $member
     * @param SubscriptionProgram $subscriptionProgram
     * @param Carbon $startAt
     * @param MemberDealer|null $fromInvite
     * @param bool $isFree 是否免費
     * @return bool
     * @throws Throwable
     */
    public function handle(
        Member              $member,
        SubscriptionProgram $subscriptionProgram,
        Carbon              $startAt,
        ?MemberDealer       $fromInvite = null,
        bool                $isFree = false
    ): bool
    {
        $this->log->info(__METHOD__ . ': start', [
            $member,
            $subscriptionProgram,
            $startAt,
            $fromInvite,
            $isFree,
        ]);

        $this->store = Store::query()->find(env('SUBSCRIPTION_PAY_CARD_STORE_ID'));

        $this->member = $member;

        $this->subscriptionProgram = $subscriptionProgram;

        $this->isFree = $isFree;

        $this->validate();

        $this->subscriptionProgramPayLog = $this->createDefaultPayLog();

        try {

            $this->subscriptionProgramCreditcardLog = $this->createDefaultCreditCardLog();

            $this->subscriptionProgramOrderIds = $this->subscriptionProgramOrderService->createDefault(
                $member,
                $this->subscriptionProgramPayLog,
                $subscriptionProgram,
                $startAt
            );

            if (!$this->swipeMemberCard()) {
                $this->subscriptionProgramOrderRepository->updateByIds(
                    $this->subscriptionProgramOrderIds,
                    ['status' => SubscriptionProgramOrder::STATUS_FAILURE],
                );

                return false;
            }

            $subscriptionBonusAmountMax = config('joymap.relation.subscription_bonus_amount_max');

            $endAt = $this->subscriptionProgramOrderService->getEndAt($startAt, $subscriptionProgram);

            /** @var MemberDealer $dealer */
            $dealer = $member->memberDealer()->updateOrCreate([], [
                'dealer_no' => $member->memberDealer?->dealer_no ?? Rand::dealerNo(),
                'status' => MemberDealer::STATUS_ENABLE,
                'rebate_balance_amount' => $member->is_joy_dealer ?
                    $member->memberDealer?->rebate_balance_amount :
                    $subscriptionBonusAmountMax,
                'subscription_program_id' => $subscriptionProgram->id,
                'next_subscription_program_id' => $subscriptionProgram->id,
                'subscription_start_at' => $member->memberDealer?->subscription_start_at ?? $startAt,
                'subscription_end_at' => $endAt,
                'from_invite_id' => $member->memberDealer?->from_invite_id ?? $fromInvite?->id,
                'join_at' => $member->memberDealer?->join_at ?? $this->subscriptionProgramCreditcardLog->created_at,
                'quit_at' => null,
            ]);

            if ($fromInvite) {
                $fromInvite->children_total = $fromInvite->inviteChildren()->count();
                $fromInvite->save();
            }

            $this->subscriptionProgramOrderRepository->updateByIds(
                $this->subscriptionProgramOrderIds,
                [
                    'status' => SubscriptionProgramOrder::STATUS_SUCCESS,
                    'member_dealer_id' => $dealer->id,
                ],
            );


            $this->memberGradeService->upgradeToDealer($member);

            return true;
        } catch (Throwable $e) {
            $this->subscriptionProgramOrderRepository->updateByIds(
                $this->subscriptionProgramOrderIds,
                ['status' => SubscriptionProgramOrder::STATUS_FAILURE],
            );

            $this->subscriptionProgramPayLog->update(['status' => SubscriptionProgramPayLog::STATUS_FAIL]);

            Log::error(__METHOD__, [$e->getMessage(), $e]);
            $message = LineNotification::getMsgText($e, __METHOD__);
            event(new SendNotifyEvent($message));

            throw $e;
        }
    }

    private function validate(): void
    {
        if (!$this->memberCreditCard = $this->memberCreditCardRepository->getDefaultSubscriptionCard($this->member->id)) {
            throw new Exception('無預設信用卡', 422);
        }

        if (!$this->subscriptionProgram->is_enabled) {
            throw new Exception('此訂閱方案已關閉', 422);
        }
    }

    private function swipeMemberCard(): bool
    {
        if ($this->isFree) {
            Log::info(__METHOD__ . ' 免刷卡', [$this->member]);

            $this->subscriptionProgramCreditcardLog->update([
                'status' => 1,
                'ret_code' => 'SUCCESS',
                'traded_at' => Carbon::now(),
            ]);

            return true;
        }

        $res = JoyPay::bySpGateway()
            ->store($this->store)
            ->orderNo($this->creditNo)
            ->money($this->subscriptionProgram->promotion_price)
            ->token($this->memberCreditCard->token)
            ->email($this->member->email)
            ->pay();

        Log::info(__METHOD__ . ' res', [$res]);

        if ($res === false) {
            return false;
        }

        try {
            $status = $res['Status'] ?? '';
            $success = ($status === 'SUCCESS');
            $resResult = $res['Result'] ?? [];

            $this->subscriptionProgramPayLog->update([
                'status' => $success ?
                    SubscriptionProgramPayLog::STATUS_SUCCESS :
                    SubscriptionProgramPayLog::STATUS_FAIL
            ]);

            $this->subscriptionProgramCreditcardLog->update([
                'bank_no' => $resResult['TradeNo'] ?? '',
                'credit_id' => $this->memberCreditCard->id,
                'subscription_program_pay_log_id' => $this->subscriptionProgramPayLog->id,
                'status' => (int)$success,
                'ret_code' => $status,
                'traded_at' => isset($resResult['AuthDate'], $resResult['AuthTime']) ?
                    Carbon::createFromFormat('YmdHis', $resResult['AuthDate'] . $resResult['AuthTime']) :
                    Carbon::now(),
                'response_log' => json_encode($res),
            ]);

            return $success;
        } catch (Throwable $e) {
            $this->subscriptionProgramPayLog->update(['status' => SubscriptionProgramPayLog::STATUS_FAIL]);

            Log::error(__METHOD__, [$e->getMessage(), $e]);
            $message = LineNotification::getMsgText($e, __METHOD__);
            event(new SendNotifyEvent($message));

            return false;
        }
    }

    private function createDefaultPayLog(): SubscriptionProgramPayLog
    {
        $createData = [
            'pay_no' => Rand::subscriptionPayNo(),
            'member_dealer_id' => $this->member->memberDealer?->id,
            'member_id' => $this->member->id,
            'status' => SubscriptionProgramPayLog::STATUS_NO_ACTION,
            'amount' => $this->subscriptionProgram->promotion_price,
            'app_version' => request()->header('version')
        ];

        return $this->subscriptionProgramPayLogRepository->create($createData);
    }

    private function createDefaultCreditCardLog(): SubscriptionProgramCreditcardLog|Model
    {
        $this->creditNo = Rand::creditNo();

        $createData = [
            'credit_id' => $this->memberCreditCard->id,
            'credit_no' => $this->creditNo,
            'credit_name' => $this->memberCreditCard->holder,
            'status' => 0,
            'amount' => $this->subscriptionProgram->promotion_price,
            'card_4_num' => $this->memberCreditCard->card_4_num,
        ];

        return $this->subscriptionProgramPayLog->creditCardLogs()->create($createData);
    }
}
