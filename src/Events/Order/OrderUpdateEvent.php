<?php

namespace Mtsung\JoymapCore\Events\Order;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\Order;

class OrderUpdateEvent
{
    use Dispatchable, SerializesModels;

    public Order $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        Log::info(__METHOD__ . ' start', [$order->id]);

        $this->order = $order;
    }
}
