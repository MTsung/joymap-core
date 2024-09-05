<?php

namespace Mtsung\JoymapCore\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static string memberNo()
 * @method static string storeNo()
 * @method static string phoneVerifyCode()
 * @method static string inviteCode()
 * @method static string dealerNo()
 * @method static string creditNo()
 * @method static string payNo()
 * @method static string subscriptionPayNo()
 * @method static string payReserveNo()
 * @method static string orderNo()
 * @method static string storePayNo()
 * @method static string numberString(int $length = 8, string $prefix = '')
 * @method static string englishNumberString(int $length = 6, string $prefix = '')
 *
 * @see \Mtsung\JoymapCore\Helpers\Rand
 */
class Rand extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Rand::class;
    }
}
