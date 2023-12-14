<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Models\JcUser;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class JcUserRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(JcUser::class);
    }

    public function getByUserId(string $userId): JcUser|Builder|null
    {
        return $this->model()->query()
            ->where('user_id', $userId)
            ->first();
    }
}