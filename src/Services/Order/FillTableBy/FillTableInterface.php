<?php

namespace Mtsung\JoymapCore\Services\Order\FillTableBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;

interface FillTableInterface
{
    public function store(Store $store): FillTableInterface;

    public function getTableCombination(Carbon $reservationDatetime, int $people, array $tableIds = []): StoreTableCombination;
}
