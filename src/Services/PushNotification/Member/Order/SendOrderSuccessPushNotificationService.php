<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderSuccessPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;
        $replace = [
            'store_name' => $order->store->name,
        ];

        if ($order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING) {
            return __('joymap::notification.order.title.success.by_store', $replace);
        }

        return __('joymap::notification.order.title.success.by_user', $replace);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderSuccessEvent $event): bool
    {
        if ($event->order->type != Order::TYPE_RESERVE) {
            return true;
        }

        return self::run($event->order->member, $event->order);
    }
}