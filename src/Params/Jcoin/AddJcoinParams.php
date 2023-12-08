<?php

namespace Mtsung\JoymapCore\Params\Jcoin;


use Mtsung\JoymapCore\Params\Validation;

class AddJcoinParams extends Validation
{
    public static function rules(): array
    {
        return [
            'title' => 'required|string',
            'use_member_id' => 'required|int',
            'use_mobile' => 'required|string|regex:/^09[0-9]{8}/|size:10',
            'transaction_type' => 'required|int',
            'user_id' => 'required|string',
            'token' => 'required|string',
            'coins' => 'required|integer',
            'start_at' => 'sometimes|nullable|date|before:expired_at',
            'expired_at' => 'required|date|date_format:Y-m-d|after:now',
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
