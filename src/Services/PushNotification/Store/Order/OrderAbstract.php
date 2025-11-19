<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Order;


use Carbon\Carbon;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Models\MainFoodType;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;

abstract class OrderAbstract extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::store;
    }

    public function body(): string
    {
        /** @var Order $order */
        $order = $this->arguments;

        $key = 'joymap::notification.order.body_to_store';
        if ($order->store->main_food_type_id == MainFoodType::ID_SERVICE) {
            $key = 'joymap::notification.order.body_to_store_service';
        }

        return __($key, [
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
