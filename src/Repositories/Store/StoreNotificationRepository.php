<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mtsung\JoymapCore\Models\StoreNotification;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StoreNotificationRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(StoreNotification::class);
    }

    /**
     * 取得店家未讀通知數量
     * @param int $storeId
     * @return int
     */
    public function getUnreadNotificationCountByStoreId(int $storeId): int
    {
        return $this->model()->query()
            ->where('store_id', $storeId)
            ->where('is_read', StoreNotification::IS_READ_OFF)
            ->count();
    }

    public function create(array $data): StoreNotification|Builder
    {
        return $this->model()->query()->create($data);
    }
}
