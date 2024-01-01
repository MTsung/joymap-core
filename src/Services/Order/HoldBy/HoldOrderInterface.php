<?php

namespace Mtsung\JoymapCore\Services\Order\HoldBy;

use Mtsung\JoymapCore\Models\Order;

interface HoldOrderInterface
{
    public function hold(Order $order): void;
}
