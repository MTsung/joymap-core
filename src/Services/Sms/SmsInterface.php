<?php

namespace Mtsung\JoymapCore\Services\Sms;


use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;

interface SmsInterface
{
    public function toType(): SmsToTypeEnum;

    public function body(array|Collection $bodyArguments = []): string;
}
