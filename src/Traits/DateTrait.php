<?php

namespace Mtsung\JoymapCore\Traits;

use Carbon\Carbon;

trait DateTrait
{
    // 時間範圍篩選
    public function formatDateRange($dateRange): array
    {
        if (empty($dateRange)) {
            return [];
        }

        $dateRangeArray = explode(' ~ ', $dateRange);
        $startAt = Carbon::parse($dateRangeArray[0]);
        $endAt = Carbon::parse($dateRangeArray[1])->endOfDay();

        return [$startAt, $endAt];
    }

    // 判斷時間是否重疊
    function isDateOverlap(Carbon $s1, Carbon $s2, Carbon $s3, Carbon $s4, $excludeEnd = true): bool
    {
        // 差異在判不判斷結尾的重疊
        // 重疊的四種可能
        // |s1------s2|
        //     |s3------s4|
        //
        //     |s1------s2|
        // |s3----s4|
        //
        // |s1---------s2|
        //    |s3---s4|
        //
        //    |s1---s2|
        // |s3---------s4|

        if ($excludeEnd) {
            // 兩種重疊為 false
            // e.g. 14:00~15:00 已有訂位，訂位時間 13:00~14:00、15:00~16:00 不能消失。
            // 以下皆為 A B 時段與 C 比較皆為 false。
            // |13---A---14|           |15---B---16|
            //             |14---C---15|
            return $s1 < $s4 && $s3 < $s2;
        } else {
            return $s1 <= $s4 && $s3 <= $s2;
        }
    }

    // 判斷單一時間是否介於這段時間內
    function isDateBetween(Carbon $check, Carbon $beginTime, Carbon $endTime): bool
    {
        return $beginTime <= $check && $check <= $endTime;
    }
}
