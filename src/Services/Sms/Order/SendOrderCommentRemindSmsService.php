<?php

namespace Mtsung\JoymapCore\Services\Sms\Order;

use Mtsung\JoymapCore\Enums\SmsToTypeEnum;
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

    public function body($bodyArguments = null): string
    {
        $order = $bodyArguments;

        return __('joymap::sms.order.comment_remind', [
            'member' => $order->name,
            'url' => config('joymap.domain.www') . '/booking-result/' . $order->id
        ]);
    }
}