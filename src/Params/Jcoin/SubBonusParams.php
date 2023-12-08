<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

class SubBonusParams extends Validation
{
    public static function rules(): array
    {
        return [
            'user_id' => 'required|string',
            'use_member_id' => 'required|int',
            'use_mobile' => 'required|string|regex:/^09[0-9]{8}/|size:10',
            'transaction_type' => 'required|int',
            'token' => 'required|string',
            'coins' => 'required|integer',
            'order_id' => 'sometimes|nullable|string',
            'comment' => 'sometimes|nullable|string',
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
