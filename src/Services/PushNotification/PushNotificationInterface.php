<?php

namespace Mtsung\JoymapCore\Services\PushNotification;


use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;

interface PushNotificationInterface
{
    public function toType(): PushNotificationToTypeEnum;

    public function title(): string;

    public function body(): string;

    public function action(): string;

    public function data(): array;

    public function success(array $request, Collection $responses): void;

    public function fail(array $request, Collection $responses): void;
}
