<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\CanOrderTime;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use Mtsung\JoymapCore\Services\Order\FillTableBy\ByMember;
use Mtsung\JoymapCore\Services\Order\FillTableBy\ByStore;
use Mtsung\JoymapCore\Services\Order\FillTableBy\FillTableInterface;

/**
 * @method static mixed run($order, array $tableIds)
 * @method static self make()
 */
class FillTableService
{
    use AsObject;

    private ?FillTableInterface $service = null;

    private ?Authenticatable $user = null;

    public function __construct(
        private CanOrderTimeRepository          $canOrderTimeRepository,
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function by(Authenticatable $user): FillTableService
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
    public function handle($order, array $tableIds): mixed
    {
        if (is_null($this->user)) {
            $this->by(Auth::user());
        }

        $this->service->store($order->store);

        $isToBeSeated = false;
        if ($order instanceof Order) {
            // 如果是既有訂單要先把自己排除掉，讓可用時間篩選不會篩到自己
            $order->update(['store_table_combination_id' => null]);

            // 現場候位入座後修改桌位照一般邏輯判斷
            $isToBeSeated = in_array($order->status, Order::TO_BE_SEATED);
        }

        if ($order->type == Order::TYPE_ONSITE_WAIT && $isToBeSeated) {
            if (count($tableIds) === 0) {
                return $order;
            }

            // 現場候位有指定位置就要算可用時間塞入
            $combination = $this->storeTableCombinationRepository->getByTableIdsOrFail($tableIds);

            $canOrderTime = $this->canOrderTimeRepository->getCanOrderTimesAndTables(
                $order->store,
                [Carbon::now(), Carbon::now()->addDay()],
                $order->from_source != Order::FROM_SOURCE_RESTAURANT_BOOKING,
                $combination->id,
                false,
            )->first(function (CanOrderTime $value) use ($order) {
                return in_array($order->people_num, Arr::collapse($value->people_array));
            });

            if (!$canOrderTime) {
                throw new Exception('該桌位於24小時內已無可用時間。', 422);
            }

            $order->begin_time = Carbon::parse($canOrderTime['begin_time']);

            $order->end_time = $order->begin_time->copy()->addMinutes($order->store->limit_minute);

            $order->store_table_combination_id = $combination->id;

            $order->store_table_combination_name = $combination->combination_name;

            return $order;
        }

        $combination = $this->service->getTableCombination(
            $order->reservation_datetime,
            $order->people_num,
            $tableIds,
        );

        $order->store_table_combination_id = $combination->id;

        $order->store_table_combination_name = $combination->combination_name;

        return $order;
    }
}