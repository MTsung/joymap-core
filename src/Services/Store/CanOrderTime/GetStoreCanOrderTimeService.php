<?php

namespace Mtsung\JoymapCore\Services\Store\CanOrderTime;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\OrderSetting;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Traits\DateTrait;
use Psr\Log\LoggerInterface;


/**
 * @method static Collection run(Store $store, Carbon[] $calDateRange = [])
 */
class GetStoreCanOrderTimeService
{
    use AsObject, DateTrait;

    // 計算用範圍
    private array $calDateRange;

    public Store $store;

    // 訂位設定
    public OrderSetting $orderSetting;

    // 營業時段
    public Collection $businessTimes;

    // 可訂位時段
    public Collection $orderHourSettings;

    // 特殊日
    public Collection $specialBusinessTimesSetting;

    // 暫停時間
    public Collection $blockTimes;

    // 用餐時間限制（分鐘）
    public int $restrictionMin = 120;

    private LoggerInterface $log;

    public function __construct()
    {
        $this->log = Log::stack([
            config('logging.default'),
            'can_order_time',
        ]);
    }

    /**
     * @param Store $store
     * @param Carbon[] $calDateRange
     * @return Collection
     */
    public function handle(Store $store, array $calDateRange = []): Collection
    {
        $this->calDateRange = $calDateRange;

        if (!$this->calDateRange) {
            // 預設計算的天數：算到最早可訂位日期的後一天（處理跨日）
            $this->calDateRange = [
                Carbon::now()->subDay()->startOfDay(),
                Carbon::now()->addDays($store->orderSettings->can_order_day)->endOfDay(),
            ];
        } else if (count($this->calDateRange) == 1) {
            // 找當天的
            $this->calDateRange = [
                $calDateRange[0]->copy()->subDay()->startOfDay(),
                $calDateRange[0]->copy()->addDay()->endOfDay(),
            ];
        }

        $this->log->info(__METHOD__ . ': calDateRange', [$this->calDateRange]);

        $this->setStore($store);

        // 先拿到只看訂位時間的列表
        $res = $this->getOrderHourSettingsList();

        // 移除每週不開放營業日期
        $closeBusinessWeeks = $this->businessTimes->where('is_open', 0)->pluck('week');
        $res = $res->whereNotIn('week', $closeBusinessWeeks);

        // 移除不在營業時間內的時間
        $res = $res->filter(function ($v) {
            $bs = $this->businessTimes->where('is_open', 1)->where('week', $v['week']);

            foreach ($bs as $businessTime) {
                $beginTime = Carbon::parse($v['date'] . ' ' . $businessTime->begin_time);
                $endTime = Carbon::parse($v['date'] . ' ' . $businessTime->end_time);
                if ($beginTime > $endTime) $endTime->addDay();

                if ($this->isDateBetween(Carbon::parse($v['begin_time']), $beginTime, $endTime)) {
                    return true;
                }
            }

            return false;
        });

        // 移除特殊日期，只要有設定當天就是照特殊日走，特殊公休這邊順便移除了
        $specialDate = $this->specialBusinessTimesSetting->pluck('special_date');
        $res = $res->whereNotIn('date', $specialDate);

        // 加上特殊營業日時間
        $specialOpenDate = $this->specialBusinessTimesSetting->where('is_open', 1);
        foreach ($specialOpenDate as $specialBusinessTime) {
            $beginTime = Carbon::parse($specialBusinessTime->special_date . ' ' . $specialBusinessTime->begin_time);
            $endTime = Carbon::parse($specialBusinessTime->special_date . ' ' . $specialBusinessTime->end_time);
            if ($beginTime > $endTime) $endTime->addDay();

            for ($now = $beginTime->copy(); $now <= $endTime; $now->addMinutes($this->orderSetting->order_unit_minute)) {
                $res[] = [
                    'store_id' => $this->store->id,
                    'date' => $specialBusinessTime->special_date,
                    'week' => $specialBusinessTime->week,
                    'begin_time' => $now->toDateTimeString(),
                    'end_time' => $now->copy()->addMinutes($this->restrictionMin)->toDateTimeString(),
                ];
            }
        }

        // 移除暫停整天訂位的日期
        $blockDate = $this->blockTimes->where('block_all_day', 1)->pluck('block_date');
        $res = $res->whereNotIn('date', $blockDate);

        // 移除暫停訂位的時間
        $boh = $this->blockTimes->where('block_all_day', 0);
        foreach ($boh as $blockOrderHour) {
            $res = $res->filter(function ($v) use ($blockOrderHour) {
                if ($v['date'] != $blockOrderHour->block_date) {
                    return true;
                }

                $beginTime = Carbon::parse($v['date'] . ' ' . $blockOrderHour->begin_time);
                $endTime = Carbon::parse($v['date'] . ' ' . $blockOrderHour->end_time);
                if ($beginTime > $endTime) $endTime->addDay();

                return !$this->isDateBetween(Carbon::parse($v['begin_time']), $beginTime, $endTime);
            });
        }

        $res = $res->sortBy('begin_time')->values();

        if (isProd()) {
            $this->log->info(__METHOD__ . ': res count', [$res->count()]);
        } else {
            $this->log->info(__METHOD__ . ': res', [$res]);
        }

        return $res;
    }

    private function setStore(Store $store): void
    {
        $this->store = $store;

        $this->restrictionMin = $store->limit_minute;

        $this->orderSetting = $store->orderSettings;

        $this->businessTimes = $store->businessTimes;

        $this->orderHourSettings = $store->orderHourSettings;

        $this->specialBusinessTimesSetting = $this->store->specialBusinessTimes()
            ->when($this->calDateRange, function ($query) {
                $query->whereBetween('special_date', $this->calDateRange);
            })
            ->get();

        $this->blockTimes = $this->store->blockOrderHour()
            ->when($this->calDateRange, function ($query) {
                $query->whereBetween('block_date', $this->calDateRange);
            })
            ->get();
    }

    // 取得正常訂位時間的 Time List
    private function getOrderHourSettingsList(): Collection
    {
        $result = [];

        $datePeriods = CarbonPeriod::create(...$this->calDateRange);

        foreach ($datePeriods as $datePeriod) {
            $date = $datePeriod->format('Y-m-d');
            $week = $datePeriod->dayOfWeek;

            if ($this->orderHourSettings->where('week', $week)->where('is_open', 0)->isNotEmpty()) {
                continue;
            }

            $ohs = $this->orderHourSettings->where('week', $week);
            foreach ($ohs as $orderHourSetting) {
                $beginTime = Carbon::parse($date . ' ' . $orderHourSetting->begin_time);
                $endTime = Carbon::parse($date . ' ' . $orderHourSetting->end_time);
                if ($beginTime > $endTime) $endTime->addDay();

                for ($now = $beginTime->copy(); $now <= $endTime; $now->addMinutes($this->orderSetting->order_unit_minute)) {
                    $result[] = [
                        'store_id' => $this->store->id,
                        'date' => $date,
                        'week' => $week,
                        'begin_time' => $now->toDateTimeString(),
                        'end_time' => $now->copy()->addMinutes($this->restrictionMin)->toDateTimeString(),
                    ];
                }
            }
        }

        return collect($result);
    }
}
