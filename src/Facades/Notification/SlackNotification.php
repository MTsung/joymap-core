<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Helpers\Notification\Slack;

/**
 * @method static void sendMsg(string $message)
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Slack
 */
class SlackNotification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Slack::class;
    }
}
