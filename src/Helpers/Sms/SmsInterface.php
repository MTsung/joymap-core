<?php

namespace Mtsung\JoymapCore\Helpers\Sms;


use Illuminate\Support\Carbon;

interface SmsInterface
{
    public function send(array $phones, string $body, ?Carbon $sendAt = null): bool;
}
