<?php

namespace Mtsung\JoymapCore\Services\Mail\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Mail\Order\OrderSuccess;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Mail\MailAbstract;

/**
 * @method static bool dispatch(Order $order)
 * @method static bool run(Order $order)
 */
class SendOrderSuccessMailService extends MailAbstract
{
    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        $email = $order->member->email;

        return $this->send($email, new OrderSuccess($order));
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderSuccessEvent $event): bool
    {
        $order = $event->order;

        return self::run($order);
    }
}
