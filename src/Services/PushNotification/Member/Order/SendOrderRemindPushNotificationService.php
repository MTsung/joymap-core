<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderRemindPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;

        return __('joymap::notification.order.title.remind', [
            'store_name' => $order->store->name,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderRemindEvent $event): bool
    {
        return self::run($event->order->member, $event->order);
    }
}