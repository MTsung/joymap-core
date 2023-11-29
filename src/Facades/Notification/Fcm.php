<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Fcm topic(string $topic)
 * @method static array formatToken(Collection $tokens)
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
