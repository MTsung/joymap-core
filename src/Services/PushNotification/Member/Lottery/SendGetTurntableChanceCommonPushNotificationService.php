<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Lottery;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Member $to, int $num)
 * @method static bool run(Member $to, int $num)
 */
class SendGetTurntableChanceCommonPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

    public function title(): string
    {
        $num = $this->arguments;

        return '【活動通知】恭喜您，獲得' . $num . '次普通轉盤的抽獎機會!';
    }

    public function body(): string
    {
        return '';
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