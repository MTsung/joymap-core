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
        $order->update([
            'end_time' => Carbon::now(),
            'status' => Order::STATUS_LEFT_SEAT,
        ]);

        $order->timeLog->update(['bill_time' => Carbon::now()]);
    }
}