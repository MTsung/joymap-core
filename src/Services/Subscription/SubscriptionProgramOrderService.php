<?php

namespace Mtsung\JoymapCore\Services\Pay;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\SubscriptionProgram;
use Mtsung\JoymapCore\Models\SubscriptionProgramOrder;
use Mtsung\JoymapCore\Models\SubscriptionProgramPayLog;
use Mtsung\JoymapCore\Repositories\Subscription\SubscriptionProgramOrderRepository;


class SubscriptionProgramOrderService
{
    private int $subscriptionPeriodDays;
    private int $subscriptionPeriodYear;

    public function __construct(
        private SubscriptionProgramOrderRepository $subscriptionProgramOrderRepository,
    )
    {
        $this->subscriptionPeriodDays = config('joymap.relation.subscription_period_days');
        $this->subscriptionPeriodYear = config('joymap.relation.subscription_period_year');
    }

    public function createDefault(
        Member                    $member,
        SubscriptionProgramPayLog $subscriptionProgramPayLog,
        SubscriptionProgram       $subscriptionProgram,
        Carbon                    $startAt,
    ): array
    {
        $startAt = $startAt->startOfDay();

        $dealer = $member->memberDealer;
        $currentPeriod = 1;
        if (!is_null($dealer) && $member->is_joy_dealer) {
            if ($lastOrder = $this->subscriptionProgramOrderRepository->getLastSuccessOrder($dealer->id)) {
                $currentPeriod = $lastOrder->current_period + 1;
            }
        }

        return DB::transaction(function () use (
            $member,
            $subscriptionProgramPayLog,
            $subscriptionProgram,
            $currentPeriod,
            $startAt
        ) {
            $orderIds = [];

            $programPeriod = $subscriptionProgram->period;
            $endAt = $this->getEndAt($startAt, $subscriptionProgram);

            for ($i = 0; $i < $programPeriod; $i++) {
                $periodStartAt = $startAt->copy()->addMonthsNoOverflow($i)->startOfDay();

                if ($programPeriod == $i + 1) {
                    $periodEndAt = $endAt;
                } else {
                    $periodEndAt = $startAt->copy()->addMonthsNoOverflow($i + 1)->subDay()->endOfDay();
                }

                $orderAttributes = [
                    'subscription_no' => Rand::subscriptionPayNo(),
                    'subscription_program_id' => $subscriptionProgram->id,
                    'subscription_program_pay_log_id' => $subscriptionProgramPayLog->id,
                    'member_id' => $member->id,
                    'status' => SubscriptionProgramOrder::STATUS_PROCESSING,
                    'current_period' => $currentPeriod++,
                    'period_start_at' => $periodStartAt->toDateTimeString(),
                    'period_end_at' => $periodEndAt->toDateTimeString(),
                ];
                $order = $this->subscriptionProgramOrderRepository->create($orderAttributes);
                $orderIds[] = $order->id;
            }

            return $orderIds;
        });
    }

    public function getEndAt(Carbon $startAt, SubscriptionProgram $subscriptionProgram): Carbon
    {
        return $startAt->copy()->addDays(
            $subscriptionProgram->period == 12 ?
                $this->subscriptionPeriodYear :
                $this->subscriptionPeriodDays
        );
    }
}
