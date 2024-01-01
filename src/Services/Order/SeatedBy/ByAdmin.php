<?php

namespace Mtsung\JoymapCore\Services\Order\SeatedBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Order;

class ByAdmin implements SeatedOrderInterface
{
    public function seated(Order $order, array $tableIds): void
    {
        $order->update([
            'status' => Order::STATUS_SEATED,
        ]);

        $order->timeLog->update(['system_seat_time' => Carbon::now()]);
    }
}