<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Services\Order\UpdateBy\ByStore;
use Mtsung\JoymapCore\Services\Order\UpdateBy\UpdateOrderInterface;
use StdClass;

/**
 * @method static self make()
 */
class UpdateOrderService
{
    use AsObject;

    private ?UpdateOrderInterface $service = null;

    private ?Authenticatable $user;

    /**
     * @throws Exception
     */
    public function by(Authenticatable $user): UpdateOrderService
    {
        $this->user = $user;

        $this->service = match (true) {
            $user instanceof StoreUser => app(ByStore::class),
        };

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(
        Order  $order,
        int    $adultNum,
        int    $childNum,
        int    $childSeatNum,
        Carbon $reservationDatetime,
        int    $goalId,
        string $storeComment,
        array  $tagIds,
        array  $tableIds,
    ): bool
    {
        Log::info('update order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
            'update_data' => [
                'adultNum' => $adultNum,
                'childNum' => $childNum,
                'childSeatNum' => $childSeatNum,
                'reservationDatetime' => $reservationDatetime,
                'goalId' => $goalId,
                'storeComment' => $storeComment,
                'tagIds' => $tagIds,
                'tableIds' => $tableIds,
            ],
        ]);

        if (!$order->isOwns($this->user)) {
            throw new Exception('無權限操作', 403);
        }

        $canUpdateStatus = [
            Order::STATUS_SUCCESS_BOOKING_BY_USER,
            Order::STATUS_SUCCESS_BOOKING_BY_STORE,
            Order::STATUS_RESERVED_SEAT,
        ];
        if (!in_array($order->status, $canUpdateStatus) && !$order->is_late) {
            throw new Exception('該訂位狀態不可修改', 422);
        }

        $res = DB::transaction(function () use ($order, $adultNum, $childNum, $childSeatNum, $reservationDatetime, $goalId, $storeComment, $tagIds, $tableIds) {
            return $this->service->update(
                $order,
                $adultNum,
                $childNum,
                $childSeatNum,
                $reservationDatetime,
                $goalId,
                $storeComment,
                $tagIds,
                $tableIds,
            );
        });

        if ($order->wasChanged(['adult_num', 'child_num', 'reservation_date', 'reservation_time'])) {
            event(new OrderUpdateEvent($order->refresh()));
        }

        return $res;
    }
}