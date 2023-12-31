<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;


use Carbon\Carbon;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;

abstract class OrderAbstract extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

    public function body(): string
    {
        $order = $this->arguments;

        $people = $order->adult_num + $order->child_num;

        return __('joymap::notification.order.body', [
            'datetime' => $order->reservation_datetime->translatedFormat('Y/m/d D H:i'),
            'people' => $people,
        ]);
    }

    public function action(): string
    {
        return 'notification.list';
    }

    public function data(): array
    {
        return [];
    }
}
