<?php

namespace Mtsung\JoymapCore\Services\Order;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use StdClass;

/**
 * @method static void run(Order $order, array $tableIds)
 */
class UpdateTableService
{
    use AsObject;

    public function __construct(
        private CanOrderTimeRepository          $canOrderTimeRepository,
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Order $order, array $tableIds): void
    {
        $user = Auth::user();

        Log::info('update table order', [
            'order_id' => $order->id,
            'user_type' => get_class($user ?? new StdClass()),
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'tableIds' => $tableIds,
        ]);

        if (!$order->isOwns($user)) {
            throw new Exception('無權限操作', 403);
        }

        $canUpdateTableStatus = [
            Order::STATUS_SUCCESS_BOOKING_BY_USER,
            Order::STATUS_SUCCESS_BOOKING_BY_STORE,
            Order::STATUS_RESERVED_SEAT,
            Order::STATUS_SEATED,
        ];
        if (!in_array($order->status, $canUpdateTableStatus) && !$order->is_late) {
            throw new Exception('該訂位狀態不可修改', 422);
        }

        FillTableService::run($this, $tableIds);

        $order->save();
    }
}