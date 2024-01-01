<?php

namespace Mtsung\JoymapCore\Services\Order\SeatedBy;

use Mtsung\JoymapCore\Models\Order;

interface SeatedOrderInterface
{
    public function seated(Order $order, array $tableIds): void;
}
