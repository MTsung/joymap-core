<?php

namespace Mtsung\JoymapCore\Services\PushNotification;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;

interface CreateOrderInterface
{
    public function store(Store $store): CreateOrderInterface;

    public function getTableCombination(Carbon $reservationDatetime, int $people, array $tableIds = []): StoreTableCombination;

    public function getStatus(int $type): int;
}
