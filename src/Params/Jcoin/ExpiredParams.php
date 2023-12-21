<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

/**
 * @method static self make($items = [])
 */
class ExpiredParams extends Validation
{
    public string $start_ts;
    public string $end_ts;

    public static function rules(): array
    {
        return [
            'start_ts' => 'required|numeric',
            'end_ts' => 'required|numeric|gte:start_ts',
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
