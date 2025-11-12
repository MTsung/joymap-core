<?php

namespace Mtsung\JoymapCore\Services\Mail\Order;

use Exception;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Mail\Order\OrderCommentRemind;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Mail\MailAbstract;

/**
 * @method static dispatch(Order $order)
 * @method static bool run(Order $order)
 */
class SendOrderCommentRemindMailService extends MailAbstract
{
    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        $email = $order->email ?? $order->member->email;

        return $this->send($email, new OrderCommentRemind($order));
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderCommentRemindEvent $event): bool
    {
        $order = $event->order;

        return self::run($order);
    }
}
