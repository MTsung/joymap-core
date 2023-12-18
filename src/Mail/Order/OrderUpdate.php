<?php

namespace Mtsung\JoymapCore\Mail\Order;

use Mtsung\JoymapCore\Enums\OrderMailTypeEnum;
use Mtsung\JoymapCore\Models\Order;

class OrderUpdate extends OrderAbstract
{
    public function __construct(Order $order)
    {
        parent::__construct($order, OrderMailTypeEnum::update);
    }
}
