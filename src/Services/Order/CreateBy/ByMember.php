<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use Mtsung\JoymapCore\Services\PushNotification\CreateOrderInterface;

class ByMember implements CreateOrderInterface
{
    private Store $store;

    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function store(Store $store): CreateOrderInterface
    {
        $this->store = $store;

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
            if ($combination = $this->storeTableCombinationRepository->getByTableIds($tableIds)) {
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
            throw new Exception('訂位已滿', 422);
        }

        return $combination;
    }

    public function getStatus(int $type): int
    {
        return Order::STATUS_SUCCESS_BOOKING_BY_USER;
    }
}