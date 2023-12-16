<?php

namespace Mtsung\JoymapCore\Services\PushNotification;


use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;

interface PushNotificationInterface
{
    public function toType(): PushNotificationToTypeEnum;

    public function title(): string;

    public function body(): string;
}
