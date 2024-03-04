<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderCommentRemindPushNotificationService extends OrderAbstract
{
    public function title(): string
    {
        $order = $this->arguments;

        return __('joymap::notification.order.title.comment_remind', [
            'store_name' => $order->store->name,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderCommentRemindEvent $event): bool
    {
        return self::run($event->order->member, $event->order);
    }
}