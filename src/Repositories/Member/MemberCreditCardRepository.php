<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberCreditCard;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberCreditCardRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberCreditCard::class);
    }

    public function getDefaultSubscriptionCard(int $memberId): MemberCreditCard|Model|null
    {
        return $this->model()
            ->query()
            ->where('member_id', $memberId)
            ->where('is_subscription_default', 1)
            ->first();
    }

    public function getDefaultCard(int $memberId): MemberCreditCard|Model|null
    {
        return $this->model()
            ->query()
            ->where('member_id', $memberId)
            ->where('is_default', 1)
            ->first();
    }
}
