<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Services\Order\HoldBy\ByMember;
use Mtsung\JoymapCore\Services\Order\HoldBy\HoldOrderInterface;
use StdClass;

/**
 * @method static void run(Order $order)
 * @method static self make()
 */
class HoldOrderService
{
    use AsObject;

    private ?HoldOrderInterface $service = null;

    private ?Authenticatable $user = null;

    public function by(Authenticatable $user): HoldOrderService
    {
        $this->user = $user;

        $this->service = match (true) {
            $user instanceof Member => app(ByMember::class),
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

        Log::info('hold order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
        ]);

        if (!$order->isOwns($this->user)) {
            throw new Exception('無權限操作', 403);
        }

        if (Carbon::now() > $order->reservation_datetime) {
            throw new Exception('已超過可以保留的時間', 422);
        }

        $canHoldStatus = [
            Order::STATUS_SUCCESS_BOOKING_BY_USER,
            Order::STATUS_SUCCESS_BOOKING_BY_STORE,
        ];
        if (!in_array($order->status, $canHoldStatus)) {
            throw new Exception('該訂位狀態不可保留', 422);
        }

        DB::transaction(function () use ($order) {
            $this->service->hold($order);
        });
    }
}