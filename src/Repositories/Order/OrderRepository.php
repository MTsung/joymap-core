<?php

namespace Mtsung\JoymapCore\Repositories\Order;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class OrderRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(Order::class);
    }

    public function hasByOrderNo(string $orderNo): bool
    {
        return $this->model()->query()
            ->where('order_no', $orderNo)
            ->exists();
    }
}
