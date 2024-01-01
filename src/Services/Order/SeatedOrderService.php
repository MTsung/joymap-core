<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\AdminUser;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Services\Order\SeatedBy\ByAdmin;
use Mtsung\JoymapCore\Services\Order\SeatedBy\ByStore;
use Mtsung\JoymapCore\Services\Order\SeatedBy\SeatedOrderInterface;
use StdClass;

/**
 * @method static void run(Order $order, array $tableIds)
 * @method static self make()
 */
class SeatedOrderService
{
    use AsObject;

    private ?SeatedOrderInterface $service = null;

    private ?Authenticatable $user = null;

    public function by(Authenticatable $user): SeatedOrderService
    {
        $this->user = $user;

        $this->service = match (true) {
            $user instanceof StoreUser => app(ByStore::class),
            $user instanceof AdminUser => app(ByAdmin::class),
        };

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(Order $order, array $tableIds): void
    {
        if (is_null($this->user)) {
            $this->by(Auth::user());
        }

        Log::info('seated order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
            'tableIds' => $tableIds,
        ]);

        if (!$order->isOwns($this->user)) {
            throw new Exception('無權限操作', 403);
        }

        $canSeatedStatus = [
            Order::STATUS_SUCCESS_BOOKING_BY_USER,
            Order::STATUS_SUCCESS_BOOKING_BY_STORE,
            Order::STATUS_RESERVED_SEAT,
        ];
        if (!in_array($order->status, $canSeatedStatus) && !$order->is_late) {
            throw new Exception('該訂位狀態不可轉為入座', 422);
        }

        if (Carbon::now() < $order->reservation_datetime->subMinutes(30)) {
            throw new Exception('尚未到達可入座時間', 422);
        }

        DB::transaction(function () use ($order, $tableIds) {
            $this->service->seated($order, $tableIds);
        });
    }
}