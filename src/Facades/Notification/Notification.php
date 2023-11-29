<?php

namespace Mtsung\JoymapCore\Facades\Notification;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification byFcm()
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification byGorush()
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification members(array $memberIds)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification member(int $memberId)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification stores(array $storeIds)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification store(int $storeId)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification token(array $token)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification tokens(array $tokens)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification title(string $title)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification body(string $body)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification badge(int $badge)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification action(string $action)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification data(array $data)
 * @method static \Mtsung\JoymapCore\Helpers\Notification\Notification topic(string $topic)
 * @method static bool send()
 *
 * @see \Mtsung\JoymapCore\Helpers\Notification\Notification
 */
class Notification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Notification\Notification::class;
    }
}
