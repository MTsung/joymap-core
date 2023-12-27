<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Order;

use Exception;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderCommentRemindPushNotificationService extends OrderAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

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
    public function asListener(OrderSuccessEvent $event): bool
    {
        return self::run($event->order->member, $event->order);
    }
}