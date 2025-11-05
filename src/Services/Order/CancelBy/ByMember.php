<?php

namespace Mtsung\JoymapCore\Services\Order\CancelBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;

class ByMember implements CancelOrderInterface
{
    /**
     * @throws Exception
     */
    public function cancel(Order $order): void
    {
        $store = $order->store;

        $storeOrderSetting = $store->orderSettings;

        // 最晚多久可取消預約(分鐘)
        $finalCancelMinute = $storeOrderSetting->final_cancel_minute;

        $finalCancelDatetime = $order->reservation_datetime->subMinutes($finalCancelMinute);
        $now = Carbon::now();
        if ($now > $finalCancelDatetime) {
            throw new Exception('已超過可以取消的時間', 422);
        }

        $order->update(['status' => Order::STATUS_CANCEL_BY_USER]);

        $order->timeLog->update(['cancel_time' => Carbon::now()]);
    }
}
