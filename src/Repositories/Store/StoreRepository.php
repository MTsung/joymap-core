<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StoreRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(Store::class);
    }

    public function hasByStoreNo(string $storeNo): bool
    {
        return $this->model()->query()
            ->where('store_no', $storeNo)
            ->exists();
    }
}
