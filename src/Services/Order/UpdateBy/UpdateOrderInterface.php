<?php

namespace Mtsung\JoymapCore\Services\Order\UpdateBy;

use Carbon\Carbon;
use Mtsung\JoymapCore\Models\Order;

interface UpdateOrderInterface
{
    public function update(
        Order  $order,
        int    $adultNum,
        int    $childNum,
        int    $childSeatNum,
        Carbon $reservationDatetime,
        int    $goalId,
        string $storeComment,
        array  $tagIds,
        array  $tableIds
    ): void;
}
