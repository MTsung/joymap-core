<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByStore implements CreateOrderInterface
{
    private Store $store;

    private int $type;

    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function store(Store $store): CreateOrderInterface
    {
        $this->store = $store;

        if ($store->can_order != Store::CAN_ORDER_ENABLED) {
            throw new Exception('訂位功能尚未啟用，無法訂位。', 422);
        }

        if (!$store->orderSettings) {
            throw new Exception('訂位規則尚未完成，請至管理中心設定。', 422);
        }

        return $this;
    }

    public function type(int $type): CreateOrderInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getTableCombination(Carbon $reservationDatetime, int $people, array $tableIds = []): StoreTableCombination
    {
        // 指定桌位
        if (count($tableIds) > 0) {
            if (!$combination = $this->storeTableCombinationRepository->getByTableIds($tableIds)) {
                throw new Exception('桌位異常', 500);
            }

            // 店家指定桌位不需檢查可不可用
            return $combination;
        }

        $combination = $this->storeTableCombinationRepository->getAvailableTable(
            $this->store,
            $reservationDatetime,
            $people,
        );

        if (!$combination) {
            throw new Exception('無可用桌位', 422);
        }

        return $combination;
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
            throw new Exception('訂位時間小於當前時間，無法訂位', 422);
        }
    }
}