<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Services\Order\CreateOrderService;

interface CreateOrderInterface
{
    public function store(Store $store): CreateOrderInterface;

    // 最終檢查
    public function check(CreateOrderService $createOrderService): CreateOrderInterface;

    public function type(int $type): CreateOrderInterface;

    public function getStatus(): int;

    public function checkPeople(int $people): void;

    public function checkReservationDatetime(Carbon $reservationDatetime): void;
}
