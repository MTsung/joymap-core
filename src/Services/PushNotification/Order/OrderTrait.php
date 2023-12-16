<?php

namespace Mtsung\JoymapCore\Traits;


use Illuminate\Support\Carbon;

trait OrderTrait
{
    // 訂單推播內容共用
    public function body(): string
    {
        $order = $this->arguments;

        $dateTime = Carbon::parse($order->reservation_date . ' ' . $order->reservation_time);
        $people = $order->adult_num + $order->child_num;

        return __('joymap::notification.order.body', [
            'datetime' => $dateTime,
            'people' => $people,
        ]);
    }
}
