<?php

namespace Mtsung\JoymapCore\Listeners\ErrorNotify;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mtsung\JoymapCore\Events\ErrorNotify\SendErrorNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;

class LineListener implements ShouldQueue
{

    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param SendErrorNotifyEvent $event
     * @return void
     * @throws Exception
     */
    public function handle(SendErrorNotifyEvent $event): void
    {
        if (config('joymap.notification.line_notify.enable')) {
            LineNotification::sendMsg($event->message);
        }
    }
}
