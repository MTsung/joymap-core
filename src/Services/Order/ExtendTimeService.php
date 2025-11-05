<?php

namespace Mtsung\JoymapCore\Services\Order;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use Mtsung\JoymapCore\Services\Order\ExtendTimeBy\ByStore;
use Mtsung\JoymapCore\Services\Order\ExtendTimeBy\ExtendTimeOrderInterface;
use StdClass;

/**
 * @method static void run(Order $order, int $minutes)
 */
class ExtendTimeService
{
    use AsObject;

    private ?ExtendTimeOrderInterface $service = null;

    private ?Authenticatable $user = null;

    public function __construct(
        private CanOrderTimeRepository          $canOrderTimeRepository,
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function by(Authenticatable $user): ExtendTimeService
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
    public function handle(Order $order, int $minutes): void
    {
        if (is_null($this->user)) {
            $this->by(Auth::user());
        }

        Log::info('extend time order', [
            'order_id' => $order->id,
            'user_type' => get_class($this->user ?? new StdClass()),
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
            'minutes' => $minutes,
        ]);

        if (!$order->isOwns($this->user)) {
            throw new Exception('無權限操作', 403);
        }

        $canExtendTimeTableStatus = [
            Order::STATUS_SEATED,
        ];
        if (!in_array($order->status, $canExtendTimeTableStatus)) {
            throw new Exception('該預約狀態不可延長', 422);
        }

        DB::transaction(function () use ($order, $minutes) {
            $this->service->extendTime($order, $minutes);
        });
    }
}
