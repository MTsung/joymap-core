<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

class CreateUserParams extends Validation
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09[0-9]{8}/|size:10',
            'nickname' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
