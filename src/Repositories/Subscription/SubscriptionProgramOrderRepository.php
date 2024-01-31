<?php

namespace Mtsung\JoymapCore\Repositories\Subscription;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\SubscriptionProgramOrder;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class SubscriptionProgramOrderRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(SubscriptionProgramOrder::class);
    }

    /**
     * 取得會員最後一筆成功訂單
     * @param int $memberDealerId
     * @return SubscriptionProgramOrder|Model|null
     */
    public function getLastSuccessOrder(int $memberDealerId): SubscriptionProgramOrder|Model|null
    {
        return $this->model()
            ->query()
            ->where('member_dealer_id', $memberDealerId)
            ->where('status', SubscriptionProgramOrder::STATUS_SUCCESS)
            ->latest()
            ->first();
    }

    public function getByInIds(array $Ids): ?Collection
    {
        return $this->model()
            ->query()
            ->whereIn('id', $Ids)
            ->get();
    }

    public function updateByIds(array $Ids, array $data): int
    {
        return $this->model()
            ->query()
            ->whereIn('id', $Ids)
            ->update($data);
    }

    public function create(array $data): SubscriptionProgramOrder|Builder
    {
        return $this->model()->query()->create($data);
    }
}
