<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberBonus;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberBonusRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberBonus::class);
    }

    public function updateMemberBonusStatus($memberId, $year, $month, $oldStatus, $newStatus): int
    {
        return $this->model()
            ->query()
            ->where([
                'status' => $oldStatus,
                'member_id' => $memberId,
                'year' => $year,
                'month' => $month,
            ])->update(['status' => $newStatus]);
    }

    public function updateMemberBonusStatusByNotDealer($memberId, $year, $month, $oldStatus, $newStatus): int
    {
        return $this->model()
            ->query()
            ->where([
                'status' => $oldStatus,
                'member_id' => $memberId,
                'year' => $year,
                'month' => $month,
            ])
            ->where('relation_level', '>', 4)
            ->update(['status' => $newStatus]);
    }
}
