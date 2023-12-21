<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\CanOrderTime;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class CanOrderTimeRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(CanOrderTime::class);
    }

    /**
     * 取得可訂位時間＆桌子人數
     * item output e.g.
     * [
     *     order_time => '2023-12-11 09:00:00',
     *     // 這是 json string
     *     people_array => '[
     *          [3, 4, 5, 6, 7, 8],
     *          [1, 2, 3, 4, 5, 6],
     *          [4, 5, 6, 7, 8, 9, 10],
     *          [4],
     *          [20, 21, 22],
     *          [5, 6, 7, 8, 9, 10],
     *          [2]
     *      ]'
     * ]
     * @throws Exception
     */
    public function getCanOrderTimesAndTables(Store $store, array $dateBetween = []): Collection
    {
        if (!$dateBetween) {
            $finalOrderMinute = $store->orderSettings->final_order_minute;
            $canOrderDay = $store->orderSettings->can_order_day;
            $dateBetween = [
                Carbon::now()->addMinutes($finalOrderMinute),
                Carbon::now()->addDays($canOrderDay - 1),
            ];
        }

        if (count($dateBetween) != 2) {
            throw new Exception('$dateBetween 必須為兩個元素的陣列');
        }

        $restrictionMin = $store->restriction?->limit_minute ?? 120;
        $checkOrderTime = Carbon::now()->subMinutes($restrictionMin);

        return $this->model()->query()
            ->select([
                'can_order_time.begin_time',
                'temp.people_array',
            ])
            ->leftJoinSub(
                $this->model()->query()
                    ->select([
                        'begin_time',
                        DB::raw('JSON_ARRAYAGG(people_combination) AS people_array'),
                    ])
                    ->leftJoin('store_table_combinations', 'store_table_combinations.store_id', 'can_order_time.store_id')
                    ->where('can_order_time.store_id', $store->id)
                    ->where('store_table_combinations.can_book_online', 1)
                    ->whereNotExists(function ($query) use ($store, $checkOrderTime) {
                        $query->from('orders')
                            ->where('orders.store_id', $store->id)
                            ->whereIn('orders.status', Order::TABLE_USING)
                            ->whereNotNull('orders.store_table_combination_id')
                            ->where('orders.begin_time', '>', $checkOrderTime)
                            ->whereColumn('orders.begin_time', '<', 'can_order_time.end_time')
                            ->whereColumn('can_order_time.begin_time', '<', 'orders.end_time')
                            ->whereRaw('JSON_CONTAINS(
                                store_table_combinations.relation_ids,
                                CONVERT(orders.store_table_combination_id,CHAR),
                                "$"
                            )');
                    })
                    ->groupBy('begin_time'),
                'temp',
                'temp.begin_time',
                'can_order_time.begin_time'
            )
            ->where('can_order_time.store_id', $store->id)
            ->whereBetween('can_order_time.begin_time', $dateBetween)
            ->orderBy('can_order_time.begin_time')
            ->get();
    }

    /**
     * @param int $storeId
     * @param ?Collection $insertData
     * @return int
     */
    public function delete(int $storeId, Collection $insertData = null): int
    {
        return $this->model()->query()
            ->where('store_id', $storeId)
            ->where(function ($query) use ($insertData) {
                $query->orWhere('begin_time', '<', Carbon::now()->subDay());
                $query->when($insertData, function ($whenQuery) use ($insertData) {
                    $whenQuery->orWhereIn('begin_time', $insertData->pluck('begin_time'));
                });
            })
            ->delete();
    }

    /**
     * 批次新增
     * @param Collection $insertData
     * @return bool
     */
    public function batchInsert(Collection $insertData): bool
    {
        $res = false;

        $chunks = $insertData->chunk(1000);
        foreach ($chunks as $chunkData) {
            $insert = $chunkData->map(fn($v) => [
                'store_id' => $v['store_id'],
                'begin_time' => $v['begin_time'],
                'end_time' => $v['end_time'],
            ])->toArray();

            if (!$temp = $this->model()->query()->insert($insert)) {
                Log::error(__METHOD__ . ': error', [$insert]);
            }

            $res |= $temp;
        }

        return $res;
    }
}