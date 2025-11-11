<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Models\MainFoodType;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;


/**
 * @method static dispatch(Member $to, Order $order)
 * @method static bool run(Member $to, Order $order)
 */
class SendOrderUpdatePushNotificationService extends OrderAbstract
{

    public function title(): string
    {
        $order = $this->arguments;

        return __('joymap::notification.order.title.update', [
            'store_name' => $order->store->name,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderUpdateEvent $event): bool
    {
        if ($event->order->store->main_food_type_id == MainFoodType::ID_SERVICE) {
            return true;
        }

        return self::run($event->order->member, $event->order);
    }
}
