<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Order;


use Carbon\Carbon;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;

abstract class OrderAbstract extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::store;
    }

    public function body(): string
    {
        $order = $this->arguments;

        return __('joymap::notification.order.body_to_store', [
            'name' => $order->name,
            'gender' => ($order->gender == 0) ? '小姐' : '先生',
            'date' => Carbon::parse($order->reservation_date)->format('Y/m/d'),
            'week' => '週' . __('joymap::week.abbr.' . $order->reservation_datetime->dayOfWeek),
            'time' => Carbon::parse($order->reservation_time)->format('H:i'),
            'adult_num' => $order->adult_num,
            'child_num' => $order->child_num
        ]);
    }

    public function action(): string
    {
        return 'order.index-list';
    }

    public function data(): array
    {
        $order = $this->arguments;

        return [
            'order_id' => $order->id,
            'name' => $order->name,
            'gender' => $order->gender,
            'adult_num' => $order->adult_num,
            'child_num' => $order->child_num,
            'reservation_date' => $order->reservation_date,
            'reservation_time' => $order->reservation_time,
            'reservation_datetime' => (string)$order->reservation_datetime,
        ];
    }
}
