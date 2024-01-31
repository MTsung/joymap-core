<?php

namespace Mtsung\JoymapCore\Repositories\Subscription;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\SubscriptionProgramPayLog;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class SubscriptionProgramPayLogRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(SubscriptionProgramPayLog::class);
    }

    public function hasByPayNo(string $payNo): bool
    {
        return $this->model()->query()
            ->where('pay_no', $payNo)
            ->exists();
    }

    public function create(array $data): SubscriptionProgramPayLog|Builder
    {
        return $this->model()->query()->create($data);
    }
}
