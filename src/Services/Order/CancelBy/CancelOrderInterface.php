<?php

namespace Mtsung\JoymapCore\Services\Order\CancelBy;

use Mtsung\JoymapCore\Models\Order;

interface CancelOrderInterface
{
    public function cancel(Order $order): bool;
}
