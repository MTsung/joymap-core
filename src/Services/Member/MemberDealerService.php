<?php

namespace Mtsung\JoymapCore\Services\Member;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Models\MemberDealer;
use Mtsung\JoymapCore\Models\MemberDealerPointLog;
use Mtsung\JoymapCore\Models\SubscriptionProgramOrder;
use Mtsung\JoymapCore\Repositories\Member\MemberDealerPointLogRepository;


class MemberDealerService
{
    private int $subscriptionInvitePoint;

    public function __construct(
        private MemberDealerPointLogRepository $memberDealerPointLogRepository,
    )
    {
        $this->subscriptionInvitePoint = config('relation.subscription_invite_point');
    }

    /**
     * 天使會員訂閱給點紀錄
     * @param MemberDealer $memberDealer 訂閱天使會員
     * @param SubscriptionProgramOrder $subscriptionProgramOrder 訂閱訂單
     * @return void
     */
    public function setDealerSubscriptionGivePoint(
        MemberDealer             $memberDealer,
        SubscriptionProgramOrder $subscriptionProgramOrder
    ): void
    {
        // 邀請人
        $inviteDealer = $memberDealer->fromInviteDealer;
        // 狀態處理
        if (is_null($inviteDealer)) {
            $status = MemberDealerPointLog::STATUS_NO_INVITE;
        } else {
            $status = $inviteDealer->status == MemberDealer::STATUS_ENABLE ?
                MemberDealerPointLog::STATUS_PROCESSING :
                MemberDealerPointLog::STATUS_CANCEL_SUBSCRIPTION;
        }

        $pointAttributes = [
            'member_dealer_id' => $inviteDealer->id ?? Null,
            'member_id' => $inviteDealer->member_id ?? Null,
            'child_dealer_id' => $memberDealer->id ?? Null,
            'subscription_program_order_id' => $subscriptionProgramOrder->id,
            'type' => MemberDealerPointLog::TYPE_JOIN_DEALER,
            'point' => $this->subscriptionInvitePoint,
            'status' => $status,
        ];

        DB::transaction(function () use ($subscriptionProgramOrder, $inviteDealer, $pointAttributes, $status) {
            $now = Carbon::now();
            if (
                $now->between($subscriptionProgramOrder->period_start_at, $subscriptionProgramOrder->period_end_at) &&
                $status == MemberDealerPointLog::STATUS_PROCESSING
            ) {
                $pointAttributes['status'] = MemberDealerPointLog::STATUS_SUCCESS;
                $pointAttributes['send_at'] = $now;
                $inviteDealer->total_point += $this->subscriptionInvitePoint;
                $inviteDealer->balance_point += $this->subscriptionInvitePoint;
                $inviteDealer->save();
            }

            $this->memberDealerPointLogRepository->create($pointAttributes);
        });
    }
}
