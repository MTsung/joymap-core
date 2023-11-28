<?php

namespace Mtsung\JoymapCore\Facades\Sms;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool send(array $phones, string $body, ?Carbon $sendAt = null)
 *
 * @see \Mtsung\JoymapCore\Helpers\Sms\Infobip
 */
class Infobip extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Sms\Infobip::class;
    }
}
