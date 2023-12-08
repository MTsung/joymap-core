<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

class CoinLogsParams extends Validation
{
    public static function rules(): array
    {
        return [
            'user_id' => 'required|string',
            'token' => 'required|string',
            'page' => 'sometimes|nullable|integer',
            'per_page_nums' => 'sometimes|nullable|integer',
        ];
    }

    public static function attributes(): array
    {
        return [];
    }

    public static function messages(): array
    {
        return [];
    }
}
