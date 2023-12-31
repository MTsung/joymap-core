<?php

namespace Mtsung\JoymapCore\Services\Order\FillTableBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByStore implements FillTableInterface
{
    private Store $store;

    public function __construct(
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function store(Store $store): FillTableInterface
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
        if (count($tableIds) > 0) {
            // 店家指定桌位不需檢查可不可用
            return $this->storeTableCombinationRepository->getByTableIdsOrFail($tableIds);
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
}