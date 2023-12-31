<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;


/**
 * @method static dispatch(Store $to, Order $order)
 * @method static bool run(Store $to, Order $order)
 */
class SendOrderSuccessPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;

        if ($order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING) {
            return __('joymap::notification.order.title.success_to_store.by_store');
        }

        return __('joymap::notification.order.title.success_to_store.by_user');
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderSuccessEvent $event): bool
    {
        if ($event->order->type != Order::TYPE_RESERVE) {
            return true;
        }

        if ($event->order->from_source != Order::FROM_SOURCE_RESTAURANT_BOOKING) {
            return true;
        }

        return self::run($event->order->store, $event->order);
    }
}
