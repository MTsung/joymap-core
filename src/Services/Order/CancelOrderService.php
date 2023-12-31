<?php

namespace Mtsung\JoymapCore\Services\Order;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Services\Order\CancelBy\ByMember;
use Mtsung\JoymapCore\Services\Order\CancelBy\ByStore;
use Mtsung\JoymapCore\Services\Order\CancelBy\CancelOrderInterface;
use StdClass;

/**
 * @method static self make()
 */
class CancelOrderService
{
    use AsObject;

    private ?CancelOrderInterface $service = null;

    private ?Authenticatable $user;

    /**
     * @throws Exception
     */
    public function by(Authenticatable $user): CancelOrderService
    {
        $this->user = $user;

        $this->service = match (true) {
            $user instanceof Member => app(ByMember::class),
            $user instanceof StoreUser => app(ByStore::class),
        };

        return $this;
    }


    /**
     * @throws Exception
     */
    public function handle(Order $order): bool
    {
        Log::info('cancel order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
        ]);

        $canCancelStatus = [
            Order::STATUS_SUCCESS_BOOKING_BY_USER,
            Order::STATUS_SUCCESS_BOOKING_BY_STORE,
            Order::STATUS_RESERVED_SEAT,
        ];
        if (!in_array($order->status, $canCancelStatus) && !$order->is_late) {
            throw new Exception('該訂位狀態不可取消', 422);
        }

        $res = DB::transaction(function () use ($order) {
            return $this->service->cancel($order);
        });

        event(new OrderCancelEvent($order->refresh()));

        return $res;
    }
}