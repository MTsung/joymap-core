<?php

namespace Mtsung\JoymapCore\Services\Sms\Order;

use Exception;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Sms\SmsAbstract;


/**
 * @method static bool dispatch(string $to, Order $order)
 * @method static bool run(string $to, Order $order)
 */
class SendOrderCommentRemindSmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body(): string
    {
        $order = $this->arguments;

        return __('joymap::sms.order.comment_remind', [
            'member' => $order->name,
            'url' => $order->info_url,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderCommentRemindEvent $event): bool
    {
        return self::run($event->order->member?->phone, $event->order);
    }
}