<?php

namespace Mtsung\JoymapCore\Services\Sms\Order;

use Exception;
use Carbon\Carbon;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Sms\SmsAbstract;


/**
 * @method static dispatch(string $to, Order $order)
 * @method static bool run(string $to, Order $order)
 */
class SendOrderRemindSmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body(): string
    {
        $order = $this->arguments;

        return __('joymap::sms.order.remind', [
            'name' => $order->store->name,
            'date' => $order->reservation_datetime->format('m/d'),
            'time' => $order->reservation_datetime->format('H:i'),
            'week' => __('joymap::week.abbr.' . $order->reservation_datetime->dayOfWeek),
            'url' => $order->info_url,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderRemindEvent $event): bool
    {
        return self::run($event->order->member?->phone, $event->order);
    }
}