<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StoreTableCombinationRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(StoreTableCombination::class);
    }

    /**
     * 用 table_id array 找出並取得桌位組合資料
     * @param array|Collection $tableIds
     * @return StoreTableCombination|Builder|null
     */
    public function getByTableIds(array|Collection $tableIds): StoreTableCombination|Builder|null
    {
        return $this->model()
            ->query()
            ->whereJsonContains('combination', $tableIds)
            ->whereJsonLength('combination', count($tableIds))
            ->first();
    }

    /**
     * @throws Exception
     */
    public function getByTableIdsOrFail(array|Collection $tableIds): StoreTableCombination
    {
        if (!$combination = $this->getByTableIds($tableIds)) {
            throw new Exception('桌位異常', 500);
        }

        return $combination;
    }

    /**
     * 取得可用桌位組合
     * @param Store $store
     * @param Carbon $reservationDatetime 預約時間
     * @param int $people 人數
     * @param bool $onlyOnline 只找可線上訂位的桌位
     * @param int $combinationId 指定桌位
     * @return StoreTableCombination|null
     */
    public function getAvailableTable(
        Store  $store,
        Carbon $reservationDatetime,
        int    $people,
        bool   $onlyOnline = false,
        int    $combinationId = 0
    ): StoreTableCombination|null
    {
        // 用餐時間
        $beginTime = Carbon::parse($reservationDatetime);
        $endTime = $beginTime->copy()->addMinutes($store->limit_minute);

        // 不撈全部的單，避免跑太慢（預測不會有延長時間超過 24 小時的單所以 subDay()）
        $checkOrderTime = $beginTime->copy()->subDay();

        return $this->model()
            ->query()
            ->where('store_table_combinations.store_id', $store->id)
            ->where('max', '>=', $people)
            ->where('min', '<=', $people)
            ->when($combinationId > 0, function ($query) use ($combinationId) {
                $query->where('store_table_combinations.id', $combinationId);
            })
            ->when($onlyOnline, function ($query) {
                $query->where('store_table_combinations.can_book_online', 1);
            })
            ->whereNotExists(function ($query) use ($store, $beginTime, $endTime, $checkOrderTime) {
                $query->from('orders')
                    ->where('orders.store_id', $store->id)
                    ->whereIn('orders.status', Order::TABLE_USING)
                    ->whereNotNull('orders.store_table_combination_id')
                    ->where('orders.begin_time', '>', $checkOrderTime)
                    ->where('orders.begin_time', '<', $endTime)
                    ->where('orders.end_time', '>', $beginTime)
                    ->whereRaw('JSON_CONTAINS(
                        store_table_combinations.relation_ids,
                        CONVERT(orders.store_table_combination_id,CHAR),
                        "$"
                    )');
            })
            // 可線上訂位的優先
            ->orderBy('can_book_online')
            // 用到桌數最少的優先
            ->orderBy(DB::raw('JSON_LENGTH(combination)'))
            // 整體可容納人數最少的優先
            ->orderBy('max')
            // 非可併桌優先
            ->orderBy('is_can_combine')
            // 關聯的組合最少的優先
            ->orderBy(DB::raw('JSON_LENGTH(relation_ids)'))
            ->first();
    }

    /**
     * 取得這店家有的桌位資訊，Google Dining spots_total 會用到
     * people => N 人桌
     * count => 有幾個這個桌
     */
    public function getPeopleNumAndCount(Store $store): Collection
    {
        return $this->model()
            ->query()
            ->select([
                'people',
                DB::raw('COUNT(*) AS count'),
            ])
            ->join(DB::raw('
                JSON_TABLE(
                    people_combination,
                    "$[*]" COLUMNS (
                        people INT PATH "$"
                    )
                ) AS t
            '), fn() => null)
            ->where('store_id', $store->id)
            ->groupBy('people')
            ->get();
    }
}
