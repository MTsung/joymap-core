<?php

namespace Mtsung\JoymapCore\Services\Order\CancelBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Order;

class ByStore implements CancelOrderInterface
{
    public function cancel(Order $order): void
    {
        $order->update([
            'is_modify' => 1,
            'status' => Order::STATUS_CANCEL_BY_STORE
        ]);

        $order->timeLog->update(['store_cancel_time' => Carbon::now()]);
    }
}