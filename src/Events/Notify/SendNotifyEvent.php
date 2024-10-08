<?php

namespace Mtsung\JoymapCore\Events\Notify;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifyEvent
{
    use Dispatchable, SerializesModels;

    public string $message;
    public string $channelId;
    public string $tagId;

    /**
     * Create a new event instance.
     *
     */
    public function __construct($message, $channelId = '', $tagId = '')
    {
        $this->message = $message;
        $this->channelId = $channelId;
        $this->tagId = $tagId;
    }
}
