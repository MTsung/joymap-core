<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Exception;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Notification\NotificationOrderRepository;

/**
 * @method static void run(Order $order, int $status)
 */
class CreateNotificationOrderService
{
    use AsObject;

    public function __construct(
        private NotificationOrderRepository $notificationOrderRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Order $order, $status): void
    {
        $this->notificationOrderRepository->createWithNotification(
            $order->id,
            $order->member_id,
            $status
        );
    }

    /**
     * @throws Exception
     */
    public function asListener(object $event): void
    {
        $order = $event->order;

        if ($event instanceof OrderSuccessEvent) {
            $status = NotificationOrder::STATUS_USER_SUCCESS;
            if ($order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING) {
                $status = NotificationOrder::STATUS_STORE_SUCCESS;
            }
        } else if ($event instanceof OrderUpdateEvent) {
            $status = NotificationOrder::STATUS_MODIFY;
        } else if ($event instanceof OrderCancelEvent) {
            $status = NotificationOrder::STATUS_USER_CANCEL;
            if ($order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING) {
                $status = NotificationOrder::STATUS_STORE_CANCEL;
            }
        } else if ($event instanceof OrderRemindEvent) {
            $status = NotificationOrder::STATUS_REMINDER;
        } else if ($event instanceof OrderCommentRemindEvent) {
            $status = NotificationOrder::STATUS_SEATED;
        } else {
            Log::error(__METHOD__ . ': 未知 event ', [get_class($event)]);
            return;
        }

        self::run($order, $status);
    }
}
