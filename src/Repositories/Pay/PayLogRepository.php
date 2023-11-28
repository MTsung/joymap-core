<?php

namespace Mtsung\JoymapCore\Repositories\Pay;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class PayLogRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(PayLog::class);
    }

    public function hasByPayNo(string $payNo): bool
    {
        return $this->model()->query()
            ->where('pay_no', $payNo)
            ->exists();
    }
}
