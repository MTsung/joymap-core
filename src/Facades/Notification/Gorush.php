<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Gorush topic(string $topic)
 * @method static array formatToken(Collection $tokens)
 * @method static bool send(array $tokens, string $title, string $body, int $badge, array $data)
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Gorush
 */
class Gorush extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Notification\Gorush::class;
    }
}
