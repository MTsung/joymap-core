<?php

namespace Mtsung\JoymapCore\Services\Mail\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Mail\Order\OrderServiceSuccess;
use Mtsung\JoymapCore\Mail\Order\OrderSuccess;
use Mtsung\JoymapCore\Models\MainFoodType;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Mail\MailAbstract;

/**
 * @method static dispatch(Order $order)
 * @method static bool run(Order $order)
 */
class SendOrderSuccessMailService extends MailAbstract
{
    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        $email = $order->email ?? $order->member->email;

        if ($order->store->main_food_type_id == MainFoodType::ID_SERVICE) {
            return $this->send($email, new OrderServiceSuccess($order));
        }

        return $this->send($email, new OrderSuccess($order));
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderSuccessEvent $event): bool
    {
        $order = $event->order;

        if ($order->type != Order::TYPE_RESERVE) {
            return true;
        }

        return self::run($order);
    }
}
