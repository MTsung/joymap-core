<?php

namespace Mtsung\JoymapCore\Services\Sms;


use Mtsung\JoymapCore\Enums\SmsToTypeEnum;

interface SmsInterface
{
    public function toType(): SmsToTypeEnum;

    public function body(mixed $bodyArguments = null): string;
}
