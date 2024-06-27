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
        return '快來試試您的手氣！';
    }

    public function body(): string
    {
        $num = $this->arguments;
        return '恭喜您，獲得' . $num . '次普通轉盤的抽獎機會!';
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