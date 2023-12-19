<?php

namespace Mtsung\JoymapCore\Services\Mail\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Mail\Order\OrderUpdate;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Mail\MailAbstract;

/**
 * @method static dispatch(Order $order)
 * @method static bool run(Order $order)
 */
class SendOrderUpdateMailService extends MailAbstract
{
    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        $email = $order->member->email;

        return $this->send($email, new OrderUpdate($order));
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderUpdateEvent $event): bool
    {
        $order = $event->order;

        return self::run($order);
    }
}
