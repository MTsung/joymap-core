<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\MemberPush;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberPushRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberPush::class);
    }

    /**
     * 取得 push device token
     * @param $memberIds
     * @return Collection
     */
    public function getTokens($memberIds): Collection
    {
        return $this->model()->query()
            ->select(['device_token', 'platform'])
            ->whereIn('member_id', $memberIds)
            ->get();
    }
}
