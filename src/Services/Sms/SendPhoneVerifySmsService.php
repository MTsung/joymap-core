<?php

namespace Mtsung\JoymapCore\Services\Sms;

use Mtsung\JoymapCore\Enums\SmsToTypeEnum;


/**
 * @method static dispatch(string $to, array $replace)
 * @method static bool run(string $to, array $replace)
 */
class SendPhoneVerifySmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body($bodyArguments = []): string
    {
        return __('joymap::sms.phone_verify', $bodyArguments);
    }
}