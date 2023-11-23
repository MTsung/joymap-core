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

    // 判斷時間是否相交
    function isDateOverlap(Carbon $s1, Carbon $s2, Carbon $s3, Carbon $s4): bool
    {
        return $s1 < $s4 && $s3 < $s2;
    }
}
