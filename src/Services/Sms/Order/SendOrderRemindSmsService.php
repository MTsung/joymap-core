<?php

namespace Mtsung\JoymapCore\Services\Sms\Order;

use Illuminate\Support\Carbon;
use Mtsung\JoymapCore\Enums\SmsToTypeEnum;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Sms\SmsAbstract;


/**
 * @method static bool dispatch(string $to, Order $order)
 * @method static bool run(string $to, Order $order)
 */
class SendOrderRemindSmsService extends SmsAbstract
{
    public function toType(): SmsToTypeEnum
    {
        return SmsToTypeEnum::phone;
    }

    public function body($bodyArguments = null): string
    {
        $order = $bodyArguments;
        $dateTime = Carbon::parse($order->reservation_date . ' ' . $order->reservation_time);

        return __('joymap::sms.order.remind', [
            'name' => $order->store->name,
            'date' => $dateTime->format('m/d'),
            'time' => $dateTime->format('H:i'),
            'week' => __('joymap::week.abbr.' . $dateTime->dayOfWeek),
            'url' => config('joymap.domain.www') . '/booking-result/' . $order->id
        ]);
    }
}