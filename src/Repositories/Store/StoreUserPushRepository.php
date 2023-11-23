<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\StoreUserPush;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StoreUserPushRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(StoreUserPush::class);
    }

    public function getTokens(array $storeIds): Collection
    {
        return $this->model()->query()
            ->select(['device_token', 'platform'])
            ->whereIn('member_id', $storeIds)
            ->get();
    }
}
