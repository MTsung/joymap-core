<?php

namespace Mtsung\JoymapCore\Repositories\Member;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberDealerPointLog;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberDealerPointLogRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberDealerPointLog::class);
    }

    public function create(array $data): MemberDealerPointLog|Builder
    {
        return $this->model()->query()->create($data);
    }
}
