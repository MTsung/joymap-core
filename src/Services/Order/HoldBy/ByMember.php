<?php

namespace Mtsung\JoymapCore\Services\Order\HoldBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Models\Order;

class ByMember implements HoldOrderInterface
{
    public function hold(Order $order): void
    {
        $order->update([
            'status' => Order::STATUS_RESERVED_SEAT,
        ]);

        $order->timeLog->update(['seat_hold_time' => Carbon::now()]);

        // 把通知的按鈕移除
        $order->notificationOrder()
            ->where('status', NotificationOrder::STATUS_REMINDER)
            ->update([
                'status' => NotificationOrder::STATUS_REMINDER_NO_BUTTON
            ]);
    }
}