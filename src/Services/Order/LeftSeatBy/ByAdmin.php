<?php

namespace Mtsung\JoymapCore\Services\Order\LeftSeatBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Order;

class ByAdmin implements LeftSeatOrderInterface
{
    public function leftSeat(Order $order): void
    {
        $order->update([
            'status' => Order::STATUS_LEFT_SEAT,
        ]);

        $order->timeLog->update(['system_bill_time' => Carbon::now()]);
    }
}