<?php

namespace Mtsung\JoymapCore\Services\Order\LeftSeatBy;

use Mtsung\JoymapCore\Models\Order;

interface LeftSeatOrderInterface
{
    public function leftSeat(Order $order): void;
}
