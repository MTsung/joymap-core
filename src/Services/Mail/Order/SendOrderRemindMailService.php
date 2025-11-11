<?php

namespace Mtsung\JoymapCore\Services\Mail\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Mail\Order\OrderRemind;
use Mtsung\JoymapCore\Mail\Order\OrderServiceRemind;
use Mtsung\JoymapCore\Models\MainFoodType;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Mail\MailAbstract;

/**
 * @method static dispatch(Order $order)
 * @method static bool run(Order $order)
 */
class SendOrderRemindMailService extends MailAbstract
{
    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        $email = $order->member->email;

        if ($order->store->main_food_type_id == MainFoodType::ID_SERVICE) {
            return $this->send($email, new OrderServiceRemind($order));
        }

        return $this->send($email, new OrderRemind($order));
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderRemindEvent $event): bool
    {
        $order = $event->order;

        return self::run($order);
    }
}
