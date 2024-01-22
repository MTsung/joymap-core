<?php

namespace Mtsung\JoymapCore\Repositories\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
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
            ->when($storeId > 0, function ($query) use ($storeId) {
                $query->where('orders.store_id', $storeId);
            })
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

    /**
     * 取得待入座的單
     * @param Store $store
     * @return Builder
     */
    public function getToBeSeatedOrders(Store $store): Builder
    {
        // 會有還在用餐時間內的遲到單 status = 7
        $beginTime = Carbon::now()->subMinutes($store->limit_minute);

        return $this->model()
            ->query()
            ->where('orders.store_id', $store->id)
            ->whereIn('orders.status', Order::TO_BE_SEATED)
            ->where('begin_time', '>=', $beginTime);
    }

    // 取得當天的最新候位號碼
    public function getWaitNumber(Store $store, Carbon $reservationDatetime): int
    {
        return 1 + ($this->model()
                ->query()
                ->where('store_id', $store->id)
                ->where('type', Order::TYPE_ONSITE_WAIT)
                ->where('reservation_date', $reservationDatetime->toDateString())
                ->orderByDesc('wait_number')
                ->first()
                ?->wait_number ?? 0);
    }

    public function create(array $data): Order|Builder
    {
        return $this->model()->query()->create($data);
    }
}
