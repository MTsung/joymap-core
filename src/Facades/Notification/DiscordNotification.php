<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Helpers\Notification\Discord;

/**
 * @method static void sendMsg(string $message, string $channelId = '', string $tagId = '')
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Discord
 */
class DiscordNotification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Discord::class;
    }
}
