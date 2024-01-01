<?php

namespace Mtsung\JoymapCore\Services\Order;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\AdminUser;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Services\Order\LeftSeatBy\ByAdmin;
use Mtsung\JoymapCore\Services\Order\LeftSeatBy\ByStore;
use Mtsung\JoymapCore\Services\Order\LeftSeatBy\LeftSeatOrderInterface;
use StdClass;

/**
 * @method static void run(Order $order)
 * @method static self make()
 */
class LeftSeatOrderService
{
    use AsObject;

    private ?LeftSeatOrderInterface $service = null;

    private ?Authenticatable $user = null;

    public function by(Authenticatable $user): LeftSeatOrderService
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
    public function handle(Order $order): void
    {
        if (is_null($this->user)) {
            $this->by(Auth::user());
        }

        Log::info('left seat order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
        ]);

        if (!$order->isOwns($this->user)) {
            throw new Exception('無權限操作', 403);
        }

        $canSeatedStatus = [
            Order::STATUS_SEATED,
        ];
        if (!in_array($order->status, $canSeatedStatus)) {
            throw new Exception('該訂位狀態不可轉為離席', 422);
        }

        DB::transaction(function () use ($order) {
            $this->service->leftSeat($order);
        });
    }
}