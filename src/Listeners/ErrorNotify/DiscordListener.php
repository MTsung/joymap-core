<?php

namespace Mtsung\JoymapCore\Listeners\ErrorNotify;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\DiscordNotification;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;

class DiscordListener implements ShouldQueue
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
        DiscordNotification::sendMsg(
            $event->message,
            $event->channelId,
            $event->tagId ?: (!isProd() ? '' : config('joymap.discord_notify.tag_id'))
        );
    }
}
