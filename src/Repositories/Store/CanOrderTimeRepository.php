<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
