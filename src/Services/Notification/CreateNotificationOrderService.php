<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Notification\NotificationOrderRepository;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Pay\PayLogRepository;

/**
 * @method static void run(Order $order, int $status)
 */
class CreateNotificationOrderService
{
    use AsObject;

    public function __construct(
        private NotificationOrderRepository $notificationOrderRepository,
        private OrderRepository             $orderRepository,
        private PayLogRepository            $payLogRepository,
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
        $status = match (true) {
            $event instanceof OrderSuccessEvent =>
            $order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING ?
                NotificationOrder::STATUS_STORE_SUCCESS :
                NotificationOrder::STATUS_USER_SUCCESS,
            $event instanceof OrderUpdateEvent => NotificationOrder::STATUS_MODIFY,
            $event instanceof OrderCancelEvent =>
            $order->status == Order::STATUS_CANCEL_BY_STORE ?
                NotificationOrder::STATUS_STORE_CANCEL :
                NotificationOrder::STATUS_USER_CANCEL,
            $event instanceof OrderRemindEvent => NotificationOrder::STATUS_REMINDER,
            $event instanceof OrderCommentRemindEvent => NotificationOrder::STATUS_SEATED,
        };

        self::run($order, $status);
    }
}
