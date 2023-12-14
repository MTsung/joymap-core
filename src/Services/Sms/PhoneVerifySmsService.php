<?php

namespace Mtsung\JoymapCore\Services\Sms;

use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;


class PhoneVerifySmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body(array|Collection $bodyArguments = []): string
    {
        return __('joymap::sms.phone_verify', $bodyArguments);
    }
}