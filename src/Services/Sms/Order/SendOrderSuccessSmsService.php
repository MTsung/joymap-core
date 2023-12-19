<?php

namespace Mtsung\JoymapCore\Services\Sms\Order;

use Exception;
use Carbon\Carbon;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Sms\SmsAbstract;


/**
 * @method static dispatch(string $to, Order $order)
 * @method static bool run(string $to, Order $order)
 */
class SendOrderSuccessSmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body(): string
    {
        $order = $this->arguments;

        $dateTime = Carbon::parse($order->reservation_date . ' ' . $order->reservation_time);

        return __('joymap::sms.order.success', [
            'name' => $order->store->name,
            'people_count' => (int)$order->adult_num + (int)$order->child_num,
            'date' => $dateTime->format('m/d'),
            'time' => $dateTime->format('H:i'),
            'week' => __('joymap::week.abbr.' . $dateTime->dayOfWeek),
            'url' => $order->info_url,
        ]);
    }

    /**
     * @throws Exception
     */
    public function asListener(OrderSuccessEvent $event): bool
    {
        return self::run($event->order->member?->phone, $event->order);
    }
}