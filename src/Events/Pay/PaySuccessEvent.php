<?php

namespace Mtsung\JoymapCore\Events\Order;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\PayLog;

class PaySuccessEvent
{
    use Dispatchable, SerializesModels;

    public PayLog $payLog;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PayLog $payLog)
    {
        Log::info(__METHOD__ . ' start', [$payLog->id]);

        $this->payLog = $payLog;
    }
}
