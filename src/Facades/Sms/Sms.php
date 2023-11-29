<?php

namespace Mtsung\JoymapCore\Facades\Sms;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms byInfobip()
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms members(array $memberIds)
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms member(int $memberId)
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms phones(array $phones)
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms phone(string $phone)
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms body(string $body)
 * @method static \Mtsung\JoymapCore\Helpers\Sms\Sms sendAt(Carbon $sendAt)
 * @method static bool onlyProdSend()
 * @method static bool send()
 *
 * @see \Mtsung\JoymapCore\Helpers\Sms\Sms
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Sms\Sms::class;
    }
}
