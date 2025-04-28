<?php

namespace Mtsung\JoymapCore\Services\Order\UpdateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Services\Order\FillTableService;

class ByMember implements UpdateOrderInterface
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function update(
        Order $order,
        int $adultNum,
        int $childNum,
        int $childSeatNum,
        Carbon $reservationDatetime,
        int $goalId,
        string $storeComment,
        array $tagIds,
        array $tableIds,
    ): void {
        // 同一時間重複訂位
        if ($order->store->orderSettings?->singleOrder ?? 1) {
            $hasRepeatOrder = $this->orderRepository->hasRepeatOrder(
                $reservationDatetime->toDateString(),
                $reservationDatetime->toTimeString(),
                $order->store_id,
                $order->member_id,
                $order->id,
            );

            if ($hasRepeatOrder) {
                throw new Exception('訂位時間重複，如欲訂位請致電餐廳', 422101);
            }
        }

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
                'begin_time' => $reservationDatetime,
                'end_time' => $reservationDatetime->copy()->addMinutes($order->store->limit_minute),
            ]);
        }

        FillTableService::make()->by($order->member)->handle($order, []);

        $order->save();
    }
}
