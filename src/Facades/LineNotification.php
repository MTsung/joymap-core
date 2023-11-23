<?php

namespace Mtsung\JoymapCore\Facades;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Helpers\Notification\Line;
use Throwable;

/**
 * @method static void sendMsg(string $message)
 * @method static string getMsgText(Throwable $e, string $uuid = '')
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Line
 */
class LineNotification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Line::class;
    }
}
