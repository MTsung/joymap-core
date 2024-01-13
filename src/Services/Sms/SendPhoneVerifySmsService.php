<?php

namespace Mtsung\JoymapCore\Services\Sms;

use Mtsung\JoymapCore\Enums\SmsToTypeEnum;


/**
 * @method static dispatch(string $to, string $code)
 * @method static bool run(string $to, string $code)
 */
class SendPhoneVerifySmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body(): string
    {
        return __('joymap::sms.phone_verify', ['code' => $this->arguments]);
    }
}