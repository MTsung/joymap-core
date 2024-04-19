<?php

namespace Mtsung\JoymapCore\Services\Member;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Models\Member;


class MemberGradeService
{
    public function upgradeToDealer(Member $member): void
    {
        if ($member->is_joy_dealer) return;

        DB::transaction(function () use ($member) {
            $member->update([
                'member_grade_id' => Member::GRADE_JOY_DEALER,
                'is_joy_dealer' => 1,
            ]);

            $member->memberGradeChangeLogs()
                ->whereNull('end_at')
                ->update(['end_at' => Carbon::now()]);

            $member->memberGradeChangeLogs()->create([
                'member_grade_id' => Member::GRADE_JOY_DEALER,
                'start_at' => Carbon::now(),
            ]);
        });
    }

    public function downgradeToNormal(Member $member): void
    {
        if (!$member->is_joy_dealer) return;

        DB::transaction(function () use ($member) {
            $member->update([
                'member_grade_id' => Member::GRADE_NORMAL,
                'is_joy_dealer' => 0,
            ]);

            $member->memberGradeChangeLogs()
                ->whereNull('end_at')
                ->update(['end_at' => Carbon::now()]);

            $member->memberGradeChangeLogs()->create([
                'member_grade_id' => Member::GRADE_NORMAL,
                'start_at' => Carbon::now(),
            ]);
        });
    }
}
