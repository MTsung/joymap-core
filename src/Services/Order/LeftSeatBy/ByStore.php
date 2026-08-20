<?php

namespace Mtsung\JoymapCore\Services\Order\LeftSeatBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByStore implements LeftSeatOrderInterface
{
    public function leftSeat(Order $order): void
    {
        // 補登記到店：未曾入座過，一律採用表定時間，不使用實際操作時間
        if ($order->status == Order::STATUS_NO_SHOW) {
            $beginTime = $order->reservation_datetime;
            $endTime = $beginTime->copy()->addMinutes($order->limit_minute ?? $order->store->limit_minute);

            $order->update([
                'begin_time' => $beginTime,
                'end_time' => $endTime,
                'status' => Order::STATUS_LEFT_SEAT,
            ]);

            $order->timeLog?->update([
                'seat_time' => $beginTime,
                'bill_time' => $endTime,
            ]);

            return;
        }

        $order->update([
            'end_time' => Carbon::now(),
            'status' => Order::STATUS_LEFT_SEAT,
        ]);

        $order->timeLog->update(['bill_time' => Carbon::now()]);
    }
}