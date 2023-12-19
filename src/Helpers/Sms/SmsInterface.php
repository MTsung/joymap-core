<?php

namespace Mtsung\JoymapCore\Helpers\Sms;


use Carbon\Carbon;

interface SmsInterface
{
    public function send(array $phones, string $body, ?Carbon $sendAt = null): bool;
}
