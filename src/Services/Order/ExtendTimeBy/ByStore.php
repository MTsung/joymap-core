<?php

namespace Mtsung\JoymapCore\Services\Order\ExtendTimeBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;

class ByStore implements ExtendTimeOrderInterface
{
    /**
     * @throws Exception
     */
    public function extendTime(Order $order, int $minutes): void
    {
        $order->update([
            'end_time' => Carbon::parse($order->end_time)->addMinutes($minutes)
        ]);
    }
}