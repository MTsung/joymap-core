<?php

namespace Mtsung\JoymapCore\Repositories\Member;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\MemberPhoneValidate;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class MemberPhoneValidateRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(MemberPhoneValidate::class);
    }

    public function getLastByPhone(string $phone): Model|null
    {
        return $this->model()
            ->query()
            ->where('phone', $phone)
            ->orderByDesc('id')
            ->first();
    }

    public function create(array $data): MemberPhoneValidate|Builder
    {
        return $this->model()->query()->create($data);
    }
}
