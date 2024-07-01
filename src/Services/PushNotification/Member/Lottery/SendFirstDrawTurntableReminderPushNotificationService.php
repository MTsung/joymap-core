<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Lottery;

use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Collection $to, string $body)
 * @method static bool run(Collection $to, string $body)
 */
class SendFirstDrawTurntableReminderPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::members;
    }

    public function title(): string
    {
        return '每日簽到轉盤來了！😍';
    }

    public function body(): string
    {
        $body = $this->arguments;
        return $body;
    }

    public function action(): string
    {
        return 'lottery.turntable.normal';
    }

    public function data(): array
    {
        return [];
    }
}