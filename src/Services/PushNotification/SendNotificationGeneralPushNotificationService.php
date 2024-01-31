<?php

namespace Mtsung\JoymapCore\Services\PushNotification;

use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\NotificationGeneral;


/**
 * @method static dispatch(Member $to, NotificationGeneral $notificationGeneral)
 * @method static bool run(Member $to, NotificationGeneral $notificationGeneral)
 */
class SendNotificationGeneralPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

    public function title(): string
    {
        $notificationGeneral = $this->arguments;

        return $notificationGeneral->title;
    }

    public function body(): string
    {
        $notificationGeneral = $this->arguments;

        return $notificationGeneral->body;
    }

    public function action(): string
    {
        $notificationGeneral = $this->arguments;

        return $notificationGeneral->action;
    }

    public function data(): array
    {
        $notificationGeneral = $this->arguments;

        return json_decode($notificationGeneral->data, true);
    }
}