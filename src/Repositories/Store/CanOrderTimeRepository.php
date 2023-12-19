<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\CanOrderTime;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class CanOrderTimeRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(CanOrderTime::class);
    }

    /**
     * @param int $storeId
     * @param ?Carbon $beginTime
     * @return int
     */
    public function delete(int $storeId, Carbon $beginTime = null): int
    {
        return $this->model()->query()
            ->where('store_id', $storeId)
            ->when($beginTime, function ($whenQuery) use ($beginTime) {
                $whenQuery->where(function ($query) use ($beginTime) {
                    $query->orWhere('begin_time', '<', Carbon::now()->subDay());
                    $query->orWhere('begin_time', '>=', $beginTime);
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

            $res |= $this->model()->query()->insert($insert);
        }

        return $res;
    }
}
