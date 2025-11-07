<?php

namespace Mtsung\JoymapCore\Services\Order\SeatedBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByStore implements SeatedOrderInterface
{
    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function seated(Order $order, array $tableIds): void
    {
        $combination = $this->storeTableCombinationRepository->getByTableIdsOrFail($tableIds);
        $limitMinute = $order->limit_minute ?? $order->store->limit_minute;
        $now = Carbon::now();
        $beginTime = Carbon::parse($order->begin_time);
        $endTime = $order->end_time ? Carbon::parse($order->end_time) : $beginTime->copy()->addMinutes($limitMinute);

        // 提早到：      現在時間～現在時間＋用餐時間
        // 遲到＋原座位： 原訂時間～原訂時間
        // 遲到＋改座位： 現在時間～現在時間＋用餐時間
        if (($now < $beginTime) || ($combination->id != $order->store_table_combination_id)) {
            $beginTime = $now;
            $endTime = $beginTime->copy()->addMinutes($limitMinute);
        }

        $order->update([
            'store_table_combination_id' => $combination->id,
            'store_table_combination_name' => $combination->combination_name,
            'begin_time' => $beginTime,
            'end_time' => $endTime,
            'status' => Order::STATUS_SEATED,
        ]);

        $order->timeLog->update(['seat_time' => Carbon::now()]);
    }
}
