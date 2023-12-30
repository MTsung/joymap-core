<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreNotification;
use Mtsung\JoymapCore\Repositories\Store\StoreNotificationRepository;

/**
 * @method static void run(Order $order, string $title, int $status)
 */
class CreateStoreNotificationOrderService
{
    use AsObject;

    public function __construct(
        private StoreNotificationRepository $storeNotificationRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Order $order, string $title, int $status): void
    {
        $data = [
            'store_id' => $order->store_id,
            'title' => $title,
            'status' => $status,
            'order_id' => $order->id,
        ];

        $this->storeNotificationRepository->create($data);
    }

    /**
     * @throws Exception
     */
    public function asListener(object $event): void
    {
        $order = $event->order;

        if ($order->type != Order::TYPE_RESERVE) {
            return;
        }

        $title = match (true) {
            $event instanceof OrderSuccessEvent =>
            $order->from_source == Order::FROM_SOURCE_RESTAURANT_BOOKING ?
                __('joymap::notification.order.title.success_to_store.by_store') :
                __('joymap::notification.order.title.success_to_store.by_user'),
            $event instanceof OrderCancelEvent =>
            $order->status == Order::STATUS_CANCEL_BY_STORE ?
                __('joymap::notification.order.title.cancel_to_store.by_store') :
                __('joymap::notification.order.title.cancel_to_store.by_user'),
        };

        $status = match (true) {
            $event instanceof OrderSuccessEvent => config('joymap.converter.store_notification.order.success')[$order->from_source],
            $event instanceof OrderCancelEvent =>
            $order->status == Order::STATUS_CANCEL_BY_STORE ?
                StoreNotification::STATUS_CANCEL_BY_STORE :
                StoreNotification::STATUS_CANCEL_BY_USER,
        };

        self::run($order, $title, $status);
    }
}
