<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\CanOrderTime;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use Mtsung\JoymapCore\Services\Order\TableBy\ByMember;
use Mtsung\JoymapCore\Services\Order\TableBy\ByStore;
use Mtsung\JoymapCore\Services\Order\TableBy\TableInterface;

/**
 * @method static mixed run($order, array $tableIds)
 */
class FillTableService
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
    public function handle($order, array $tableIds): mixed
    {
        /** @var TableInterface $byService */
        $byService = match ($order->fromSource) {
            Order::FROM_SOURCE_RESTAURANT_BOOKING => app(ByStore::class),
            default => app(ByMember::class),
        };

        $byService->store($order->store);

        if ($order->type == Order::TYPE_ONSITE_WAIT) {
            if (count($tableIds) === 0) {
                return $order;
            }

            // 現場候位有指定位置就要算可用時間塞入
            $combination = $this->storeTableCombinationRepository->getByTableIdsOrFail($tableIds);

            $canOrderTime = $this->canOrderTimeRepository->getCanOrderTimesAndTables(
                $order->store,
                [Carbon::now(), Carbon::now()->addDay()],
                $order->fromSource != Order::FROM_SOURCE_RESTAURANT_BOOKING,
                $combination->id,
                false,
            )->first(function (CanOrderTime $value) use ($order) {
                return in_array($order->peopleNum, Arr::collapse($value->people_array));
            });

            if (!$canOrderTime) {
                throw new Exception('該桌位於24小時內已無可用時間。', 422);
            }

            $order->beginTime = Carbon::parse($canOrderTime['begin_time']);

            $order->endTime = $order->beginTime->copy()->addMinutes($order->store->limit_minute);

            $order->storeTableCombinationId = $combination->id;

            $order->storeTableCombinationName = $combination->combination_name;

            return $order;
        }

        $combination = $byService->getTableCombination(
            $order->reservationDatetime,
            $order->peopleNum,
            $tableIds,
        );

        $order->storeTableCombinationId = $combination->id;

        $order->storeTableCombinationName = $combination->combination_name;

        return $order;
    }
}