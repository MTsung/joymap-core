<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

class ExpiredParams extends Validation
{
    public string $start_ts;
    public string $end_ts;

    public function rules(): array
    {
        return [
            'start_ts' => 'required|numeric',
            'end_ts' => 'required|numeric|gte:start_ts',
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
