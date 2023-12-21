<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

/**
 * @method static self make($items = [])
 */
class GetUserInfoParams extends Validation
{
    public static function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09[0-9]{8}/|size:10',
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
