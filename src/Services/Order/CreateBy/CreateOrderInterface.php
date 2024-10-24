<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;

interface CreateOrderInterface
{
    public function store(Store $store): CreateOrderInterface;

    public function check(Store $store, Member $member): CreateOrderInterface;

    public function type(int $type): CreateOrderInterface;

    public function getStatus(): int;

    public function checkPeople(int $people): void;

    public function checkReservationDatetime(Carbon $reservationDatetime): void;
}
