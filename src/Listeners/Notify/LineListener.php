<?php

namespace Mtsung\JoymapCore\Listeners\Notify;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;

class LineListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param SendNotifyEvent $event
     * @return void
     * @throws Exception
     */
    public function handle(SendNotifyEvent $event): void
    {
        LineNotification::sendMsg($event->message, !isProd());
    }
}
