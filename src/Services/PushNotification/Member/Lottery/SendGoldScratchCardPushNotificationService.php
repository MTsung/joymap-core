<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Lottery;

use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\NotificationGeneral;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Member $to, NotificationGeneral $notificationGeneral)
 * @method static bool run(Member $to, NotificationGeneral $notificationGeneral)
 */
class SendGoldScratchCardPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

    public function title(): string
    {
        return '【活動通知】恭喜您，獲得黃金刮刮卡一張！';
    }

    public function body(): string
    {
        return '';
    }

    public function action(): string
    {
        return 'lottery.scratcher.gold';
    }

    public function data(): array
    {
        return [];
    }
}