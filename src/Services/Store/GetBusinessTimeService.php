<?php

namespace Mtsung\JoymapCore\Services\Store;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Traits\DateTrait;


/**
 * @method static Collection run(Store $store, Carbon[] $calDateRange = [])
 */
class GetBusinessTimeService
{
    use AsObject, DateTrait;

    // 計算用範圍
    private array $calDateRange;

    public Store $store;

    // 營業時段
    public Collection $businessTimes;

    // 特殊日
    public Collection $specialBusinessTimesSetting;

    /**
     * @param Store $store
     * @param Carbon[] $calDateRange
     * @return Collection
     */
    public function handle(Store $store, array $calDateRange = []): Collection
    {
        $this->calDateRange = $calDateRange;

        if (!$this->calDateRange) {
            // 預設計算的天數：一個月
            $this->calDateRange = [
                Carbon::now()->subDay()->startOfDay(),
                Carbon::now()->addMonth()->endOfDay(),
            ];
        } else if (count($this->calDateRange) == 1) {
            // 找當天的
            $this->calDateRange = [
                $calDateRange[0]->copy()->subDay()->startOfDay(),
                $calDateRange[0]->copy()->addDay()->endOfDay(),
            ];
        }

        $this->setStore($store);

        // 先拿到只看預約時間的列表
        $res = $this->getBusinessTimesList();

        // 移除特殊日期，只要有設定當天就是照特殊日走，特殊公休這邊順便移除了
        $specialDate = $this->specialBusinessTimesSetting->pluck('special_date');
        $res = $res->whereNotIn('date', $specialDate);

        // 加上特殊營業日時間
        $specialOpenDate = $this->specialBusinessTimesSetting->where('is_open', 1);
        foreach ($specialOpenDate as $specialBusinessTime) {
            $beginTime = Carbon::parse($specialBusinessTime->special_date . ' ' . $specialBusinessTime->begin_time);
            $endTime = Carbon::parse($specialBusinessTime->special_date . ' ' . $specialBusinessTime->end_time);
            if ($beginTime > $endTime) $endTime->addDay();

            $res[] = [
                'store_id' => $this->store->id,
                'date' => $specialBusinessTime->special_date,
                'week' => $specialBusinessTime->week,
                'begin_time' => $beginTime,
                'end_time' => $endTime,
            ];
        }

        return $res->sortBy('begin_time')->values();
    }

    private function setStore(Store $store): void
    {
        $this->store = $store;

        $this->businessTimes = $store->businessTimes ?? collect();

        $this->specialBusinessTimesSetting = $this->store->specialBusinessTimes?->when($this->calDateRange, function ($query) {
            $query->whereBetween('special_date', $this->calDateRange);
        }) ?? collect();
    }

    // 取得正常營業時間的 Time List
    private function getBusinessTimesList(): Collection
    {
        $result = [];

        $datePeriods = CarbonPeriod::create(...$this->calDateRange);

        foreach ($datePeriods as $datePeriod) {
            $date = $datePeriod->format('Y-m-d');
            $week = $datePeriod->dayOfWeek;

            if ($this->businessTimes->where('week', $week)->where('is_open', 0)->isNotEmpty()) {
                continue;
            }

            $bs = $this->businessTimes->where('week', $week);
            foreach ($bs as $businessTimes) {
                $beginTime = Carbon::parse($date . ' ' . $businessTimes->begin_time);
                $endTime = Carbon::parse($date . ' ' . $businessTimes->end_time);
                if ($beginTime > $endTime) $endTime->addDay();

                $result[] = [
                    'store_id' => $this->store->id,
                    'date' => $date,
                    'week' => $week,
                    'begin_time' => $beginTime,
                    'end_time' => $endTime,
                ];
            }
        }

        return collect($result);
    }
}
