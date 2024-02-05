<?php

namespace Mtsung\JoymapCore\Repositories\JoyPay;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\PayReserve;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class PayReserveRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(PayReserve::class);
    }

    public function hasByReserveNo(string $reserveNo): bool
    {
        return $this->model()->query()
            ->where('reserve_no', $reserveNo)
            ->exists();
    }
}
