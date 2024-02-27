<?php

namespace Mtsung\JoymapCore\Services\Order\UpdateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Order\FillTableService;

class ByMember implements UpdateOrderInterface
{
    /**
     * @throws Exception
     */
    public function update(
        Order  $order,
        int    $adultNum,
        int    $childNum,
        int    $childSeatNum,
        Carbon $reservationDatetime,
        int    $goalId,
        string $storeComment,
        array  $tagIds,
        array  $tableIds,
    ): void
    {
        $order->fill([
            'is_modify' => 1,
            'adult_num' => $adultNum,
            'child_num' => $childNum,
            'child_seat_num' => $childSeatNum,
        ]);

        if ($order->type != Order::TYPE_ONSITE_WAIT) {
            $order->fill([
                'reservation_date' => $reservationDatetime->toDateString(),
                'reservation_time' => $reservationDatetime->toTimeString(),
            ]);
        }

        FillTableService::make()->by($order->member)->handle($order, []);

        $order->save();
    }
}