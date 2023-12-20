<?php

namespace Mtsung\JoymapCore\Repositories\StoreWallet;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\StoreWallet;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class StoreWalletRepository implements RepositoryInterface

{
    public function model(): Model
    {
        return app(StoreWallet::class);
    }

    public function create(array $data): StoreWallet|Builder
    {
        return $this->model()->query()->create($data);
    }
}
