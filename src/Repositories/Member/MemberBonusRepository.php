<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberBonus;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberBonusRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberBonus::class);
    }

    public function updateMemberBonusStatus(int $memberId, Carbon $startAt, Carbon $endAt, int $oldStatus, int $newStatus): int
    {
        return $this->model()
            ->query()
            ->leftJoin('pay_logs', 'pay_logs.id', 'member_bonus.pay_log_id')
            ->where('member_bonus.status', $oldStatus)
            ->where('member_bonus.member_id', $memberId)
            ->whereBetween('pay_logs.created_at', [$startAt, $endAt])
            ->update(['member_bonus.status' => $newStatus]);
    }
}
