<?php

namespace Mtsung\JoymapCore\Services\Order\ExtendTimeBy;

use Mtsung\JoymapCore\Models\Order;

interface ExtendTimeOrderInterface
{
    public function extendTime(Order $order, int $minutes): void;
}
