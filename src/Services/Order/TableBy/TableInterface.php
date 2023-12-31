<?php

namespace Mtsung\JoymapCore\Services\Order\TableBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;

interface TableInterface
{
    public function store(Store $store): TableInterface;

    public function getTableCombination(Carbon $reservationDatetime, int $people, array $tableIds = []): StoreTableCombination;
}
