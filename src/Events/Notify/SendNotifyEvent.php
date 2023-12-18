<?php

namespace Mtsung\JoymapCore\Events\Notify;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifyEvent
{
    use Dispatchable, SerializesModels;

    public string $message;

    /**
     * Create a new event instance.
     *
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
