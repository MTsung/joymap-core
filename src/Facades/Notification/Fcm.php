<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool send(array $tokens, string $title, string $body, int $badge, array $data)
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Fcm
 */
class Fcm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Notification\Fcm::class;
    }
}
