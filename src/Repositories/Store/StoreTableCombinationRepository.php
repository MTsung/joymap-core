<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
