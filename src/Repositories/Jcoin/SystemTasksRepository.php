<?php

namespace Mtsung\JoymapCore\Repositories\Jcoin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\SystemTask;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class SystemTasksRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(SystemTask::class);
    }

    public function createOrFirst(string $name): SystemTask|Builder|null
    {
        $query = $this->model()->query();
        if ($systemTask = $query->where('name', $name)->first()) {
            return $systemTask;
        }

        $maxType = $this->model()->query()->max('type');

        return $query->create([
            'name' => $name,
            'type' => $maxType + 1,
            'is_active' => 1,
        ]);
    }
}
