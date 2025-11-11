<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Models\MainFoodType;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderCancelPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;
        $replace = [
            'store_name' => $order->store->name,
        ];

        if ($order->status == Order::STATUS_CANCEL_BY_STORE) {
            return __('joymap::notification.order.title.cancel.by_store', $replace);
        }

        return __('joymap::notification.order.title.cancel.by_user', $replace);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderCancelEvent $event): bool
    {
        if ($event->order->store->main_food_type_id == MainFoodType::ID_SERVICE) {
            return true;
        }

        return self::run($event->order->member, $event->order);
    }
}
