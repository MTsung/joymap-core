<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(Member::class);
    }

    public function getPhones($memberIds): Collection
    {
        return $this->model()->query()
            ->select(['phone_prefix', 'phone'])
            ->whereIn('id', $memberIds)
            ->get()
            ->append(['full_phone']);
    }

    public function hasByInviteCode(string $inviteCode): bool
    {
        return $this->model()->query()
            ->where('invite_code', $inviteCode)
            ->exists();
    }

    public function hasByMemberNo(string $memberNo): bool
    {
        return $this->model()->query()
            ->where('member_no', $memberNo)
            ->exists();
    }
}
