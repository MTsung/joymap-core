<?php

namespace Mtsung\JoymapCore\Services\Store\CanOrderTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsJob;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Traits\DateTrait;


/**
 * @method static dispatch(Store $store, bool $isNewDay = false)
 * @method static bool run(Store $store, bool $isNewDay = false)
 */
class WriteStoreCanOrderTimeService
{
    use AsObject, AsJob, DateTrait;

    public function __construct(
        private CanOrderTimeRepository $canOrderTimeRepository,
    )
    {
    }

    public function handle(Store $store, bool $isNewDay = false): bool
    {
        return DB::transaction(function () use ($store, $isNewDay) {

            $log = Log::stack([
                config('logging.default'),
                'can_order_time',
            ]);

            $log->info(__METHOD__ . ': start', [$store->id]);

            $canOrderTime = GetStoreCanOrderTimeService::run(
                $store,
                $isNewDay ? [
                    Carbon::now()->addDays($store->orderSettings->can_order_day)->startOfDay(),
                    Carbon::now()->addDays($store->orderSettings->can_order_day)->endOfDay(),
                ] : [],
            );

            $beginTime = null;
            if ($firstCanOrderTime = $canOrderTime->first()) {
                $beginTime = Carbon::parse($firstCanOrderTime['begin_time']);
            }

            $log->info(__METHOD__ . ': delete', [$store->id, $beginTime]);

            $this->canOrderTimeRepository->delete($store->id, $beginTime);

            $res = $this->canOrderTimeRepository->batchInsert($canOrderTime);

            $log->info(__METHOD__ . ': batchInsert end', [$res]);

            return $res;
        });
    }
}
