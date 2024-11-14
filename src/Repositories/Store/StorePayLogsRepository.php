<?php

namespace Mtsung\JoymapCore\Repositories\Store;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\StorePayLogs;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StorePayLogsRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(StorePayLogs::class);
    }

    public function hasByPayNo(string $payNo): bool
    {
        return $this->model()->query()
            ->where('pay_no', $payNo)
            ->exists();
    }

    public function create(array $data): StorePayLogs|Builder
    {
        return $this->model()->query()->create($data);
    }
}
