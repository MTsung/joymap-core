<?php

namespace Mtsung\JoymapCore\Events\ErrorNotify;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendErrorNotifyEvent
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
