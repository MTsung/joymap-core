<?php

namespace Mtsung\JoymapCore\Services\Order\TableBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByMember implements TableInterface
{
    private Store $store;

    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function store(Store $store): TableInterface
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
            $combination = $this->storeTableCombinationRepository->getByTableIdsOrFail($tableIds);
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
}