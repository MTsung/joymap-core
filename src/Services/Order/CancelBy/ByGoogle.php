<?php

namespace Mtsung\JoymapCore\Services\Order\CancelBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;

class ByGoogle implements CancelOrderInterface
{
    /**
     * @throws Exception
     */
    public function cancel(Order $order): void
    {
        $order->update(['status' => Order::STATUS_CANCEL_BY_USER]);

        $order->timeLog->update(['cancel_time' => Carbon::now()]);
    }
}