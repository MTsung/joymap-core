<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByMember implements CreateOrderInterface
{
    private Store $store;

    private int $type;

    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function store(Store $store): CreateOrderInterface
    {
        $this->store = $store;

        if ($store->can_order != Store::CAN_ORDER_ENABLED) {
            throw new Exception('該店家訂位功能尚未啟用', 422);
        }

        if (!$store->orderSettings) {
            throw new Exception('order_setting 設定異常: ' . $store->id, 500);
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
        $combination = null;
        if (count($tableIds) > 0) {
            if (!$combination = $this->storeTableCombinationRepository->getByTableIds($tableIds)) {
                throw new Exception('桌位異常', 500);
            }
        }

        $combination = $this->storeTableCombinationRepository->getAvailableTable(
            $this->store,
            $reservationDatetime,
            $people,
            true,
            $combination?->id ?? 0,
        );

        if (!$combination) {
            throw new Exception('該時段訂位已滿', 422);
        }

        return $combination;
    }

    public function getStatus(): int
    {
        return Order::STATUS_SUCCESS_BOOKING_BY_USER;
    }

    /**
     * @throws Exception
     */
    public function checkPeople(int $people): void
    {
        if ($people > $this->store->orderSettings->single_time_order_total) {
            throw new Exception('超過單次訂位人數上限', 422);
        }
    }

    /**
     * @throws Exception
     */
    public function checkReservationDatetime(Carbon $reservationDatetime): void
    {
        if ($reservationDatetime < Carbon::now()) {
            throw new Exception('訂位時間小於當前時間，無法訂位', 422);
        }

        if ($this->type == Order::TYPE_RESERVE) {
            $finalOrderMinute = $this->store->orderSettings->final_order_minute;
            if ($reservationDatetime < Carbon::now()->addMinutes($finalOrderMinute)) {
                throw new Exception('該時間已不可預約', 422);
            }

            $canOrderDay = $this->store->orderSettings->can_order_day;
            if ($reservationDatetime > Carbon::now()->addDays($canOrderDay - 1)) {
                throw new Exception('該時間尚未開放預約', 422);
            }
        }
    }
}