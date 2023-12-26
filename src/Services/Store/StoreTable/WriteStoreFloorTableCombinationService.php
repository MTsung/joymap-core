<?php

namespace Mtsung\JoymapCore\Services\Store\StoreTable;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorUpdatedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableUpdatedEvent;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreFloor;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

/**
 * @method static dispatch(StoreFloor $storeFloor, bool $refreshOrderTableId = true)
 * @method static bool run(StoreFloor $storeFloor, bool $refreshOrderTableId = true)
 */
class WriteStoreFloorTableCombinationService
{
    use AsObject;

    public StoreFloor $storeFloor;
    public Collection $storeTables;

    public function __construct(
        private OrderRepository                 $orderRepository,
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function handle(StoreFloor $storeFloor, bool $refreshOrderTableId = true): void
    {
        $storeFloor->loadMissing([
            'storeTables.combineTables',
        ]);

        $this->storeFloor = $storeFloor;
        $this->storeTables = $storeFloor->storeTables;

        DB::transaction(fn() => $this->write($this->getFloorTableCombination()));

        if ($refreshOrderTableId) {
            DB::transaction(fn() => $this->refreshOrderTable());
        }
    }

    public function asListener(object $event): void
    {
        $storeFloor = match (true) {
            $event instanceof StoreFloorUpdatedEvent => $event->storeFloor,
            $event instanceof StoreTableUpdatedEvent => $event->storeTable->storeFloor,
            $event instanceof StoreTableDeletedEvent => $event->storeTable->storeFloor,
        };

        self::run($storeFloor);
    }

    /**
     * 取得樓層所有併桌組合
     */
    private function getFloorTableCombination(): Collection
    {
        $combinations = collect();
        foreach ($this->storeTables as $storeTable) {
            $tableIds = $storeTable->combineTables->pluck('id');
            $combinations = $combinations->merge($this->getAllSubsets($tableIds, $storeTable->id));
        }

        return $combinations->unique();
    }

    private function write(array|Collection $combinations): void
    {
        $storeId = $this->storeFloor->store_id;
        $this->storeFloor->storeTableCombinations()->delete();
        foreach ($combinations as $tableId) {
            $tables = $this->storeTables->whereIn('id', $tableId);
            $min = $tables->sum('min');
            $max = $tables->sum('max');
            $this->storeFloor->storeTableCombinations()->create([
                'min' => $min,
                'max' => $max,
                'people_combination' => range($min, $max),
                'can_book_online' => !$tables->contains('can_book_online', 0),
                'is_can_combine' => $tables->contains('can_combine', 1),
                'combination' => $tableId,
                'combination_name' => $tables->pluck('name')->implode('、'),
                'store_id' => $storeId,
            ]);
        }

        $this->storeFloor->storeTableCombinations()->update([
            'relation_ids' => DB::raw('(
                SELECT JSON_ARRAYAGG(sub.id)
                FROM (
                    SELECT id
                    FROM store_table_combinations AS temp
                    WHERE JSON_OVERLAPS(store_table_combinations.combination, temp.combination)
                ) AS sub
            )')
        ]);
    }

    private function refreshOrderTable(): void
    {
        // storeTableCombinations 有異動所以重撈
        $storeTableCombinations = $this->storeFloor->storeTableCombinations();

        $orders = $this->orderRepository->getToBeSeatedOrders($this->storeFloor->store)->get();

        /** @var Order $order */
        foreach ($orders as $order) {
            Log::info(__METHOD__ . ' refresh order:', [$order]);

            $people = $order->adult_num + $order->child_num;

            // 先找同名
            $table = $storeTableCombinations
                ->where('combination_name', $order->store_table_combination_name)
                ->where('min', '<=', $people)
                ->where('max', '>=', $people)
                ->first();

            Log::info(__METHOD__ . ' 找同名 table:', [$table]);

            // 沒同名 或 同名不符合條件 就重新排位置
            if (!$table) {
                $table = $this->storeTableCombinationRepository->getAvailableTable(
                    $this->storeFloor->store,
                    Carbon::parse($order->begin_time),
                    $people,
                    $order->from_source != Order::FROM_SOURCE_RESTAURANT_BOOKING,
                );

                Log::info(__METHOD__ . ' 找不同名 table:', [$table]);
            }

            $order->update([
                'store_table_combination_id' => $table?->id,
                'store_table_combination_name' => $table?->combination_name,
            ]);
        }
    }

    /**
     * 取得所有子集合
     * e.g.
     * input: [1, 2]
     * output: [[1], [2], [1, 2]]
     * @param array|Collection $input
     * @param ?int $append
     * @return array[]
     */
    function getAllSubsets(array|Collection $input, int $append = null): array
    {
        $result = [];
        $result[] = isset($append) ? [$append] : [];

        foreach ($input as $v) {
            foreach ($result as $rv) {
                $result[] = array_merge($rv, [$v]);
            }
        }

        return collect($result)->map(function ($v) {
            sort($v);
            return $v;
        })->filter()->values()->toArray();
    }
}
