<?php

namespace Mtsung\JoymapCore\Repositories\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class OrderRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(Order::class);
    }

    public function hasByOrderNo(string $orderNo): bool
    {
        return $this->model()->query()
            ->where('order_no', $orderNo)
            ->exists();
    }

    public function getByStoreAndMember(int $storeId, int $memberId = 0)
    {
        return $this->model()
            ->query()
            ->select('orders.*')
            ->addReservationDatetime()
            ->where('orders.store_id', $storeId)
            ->when($memberId > 0, function ($query) use ($memberId) {
                $query->where('orders.member_id', $memberId);
            });
    }

    // 取得來店的資料 (已入座、離席)
    public function getSuccessLog(int $storeId, int $memberId, array $dateRange = [])
    {
        return $this->getByStoreAndMember($storeId, $memberId)
            ->whereIn('orders.status', Order::VISIT)
            ->when(count($dateRange) == 2, function ($query) use ($dateRange) {
                $query->whereBetween(
                    DB::raw(Order::RAW_RESERVATION_DATETIME),
                    $dateRange
                );
            });
    }
}
