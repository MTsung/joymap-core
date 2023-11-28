<?php

namespace Mtsung\JoymapCore\Repositories\MemberDealer;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberDealer;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberDealerRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberDealer::class);
    }

    public function hasByDealerNo(string $dealerNo): bool
    {
        return $this->model()->query()
            ->where('dealer_no', $dealerNo)
            ->exists();
    }
}
