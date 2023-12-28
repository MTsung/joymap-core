<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;

interface CreateOrderInterface
{
    public function store(Store $store): CreateOrderInterface;

    public function type(int $type): CreateOrderInterface;

    public function getTableCombination(Carbon $reservationDatetime, int $people, array $tableIds = []): StoreTableCombination;

    public function getStatus(): int;

    public function checkPeople(int $people): void;

    public function checkReservationDatetime(Carbon $reservationDatetime): void;
}
