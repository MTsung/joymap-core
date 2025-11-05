<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Services\Order\CreateOrderService;

class ByStore implements CreateOrderInterface
{
    private int $type;

    public function store(Store $store): CreateOrderInterface
    {
        if ($store->can_order != Store::CAN_ORDER_ENABLED) {
            throw new Exception('預約功能尚未啟用，無法預約。', 422);
        }

        if (!$store->orderSettings) {
            throw new Exception('預約規則尚未完成，請至管理中心設定。', 422);
        }

        return $this;
    }

    public function type(int $type): CreateOrderInterface
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): int
    {
        if ($this->type == Order::TYPE_ONSITE_SEAT) {
            return Order::STATUS_SEATED;
        }

        return Order::STATUS_SUCCESS_BOOKING_BY_STORE;
    }

    public function checkPeople(int $people): void
    {
        // 店家目前無限制
    }

    /**
     * @throws Exception
     */
    public function checkReservationDatetime(Carbon $reservationDatetime): void
    {
        if ($reservationDatetime < Carbon::now()) {
            throw new Exception('預約時間小於當前時間，無法預約', 422);
        }
    }

    public function check(CreateOrderService $createOrderService): CreateOrderInterface
    {
        return $this;
    }
}
