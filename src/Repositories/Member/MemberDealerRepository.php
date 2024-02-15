<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Builder;
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

    public function getByPhoneOrNo(string $keyword): MemberDealer|Model|null
    {
        return $this->model()->query()
            ->join('members', 'members.id', 'member_dealers.member_id')
            ->where(function ($query) use ($keyword) {
                $query->orWhere('members.phone', $keyword);
                $query->orWhere('member_dealers.dealer_no', $keyword);
            })
            ->first();
    }

    public function create(array $data): MemberDealer|Builder
    {
        return $this->model()->query()->create($data);
    }
}
