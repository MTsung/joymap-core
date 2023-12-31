<?php

namespace Mtsung\JoymapCore\Services\Order\CancelBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;

class ByStore implements CancelOrderInterface
{
    /**
     * @throws Exception
     */
    public function cancel(Order $order): bool
    {
        $res = $order->update([
            'is_modify' => 1,
            'status' => Order::STATUS_CANCEL_BY_STORE
        ]);

        return $res && $order->timeLog->update(['store_cancel_time' => Carbon::now()]);
    }
}