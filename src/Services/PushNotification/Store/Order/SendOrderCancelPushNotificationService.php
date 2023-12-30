<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;


/**
 * @method static dispatch(Store $to, Order $order)
 * @method static bool run(Store $to, Order $order)
 */
class SendOrderCancelPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;

        if ($order->status == Order::STATUS_CANCEL_BY_STORE) {
            return __('joymap::notification.order.title.cancel_to_store.by_store');
        }

        return __('joymap::notification.order.title.cancel_to_store.by_user');
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderCancelEvent $event): bool
    {
        return self::run($event->order->store, $event->order);
    }
}
