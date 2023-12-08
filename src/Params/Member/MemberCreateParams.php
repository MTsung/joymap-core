<?php

namespace Mtsung\JoymapCore\Params\Member;


use Illuminate\Validation\Rule;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Params\Validation;

class MemberCreateParams extends Validation
{
    public static function rules(): array
    {
        return [
            // '0987086921', '+886 987-086-921', '+886 987086921', '+886 987 086 921', '+886987086921'
            'full_phone' => 'required_without:apple_id|string',
            // 手機或是 apple id 只要其一存在即可成為會員
            'apple_id' => 'required_without:full_phone|string',
            // raw password
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable|in:0,1',
            'name' => 'required|string',
            'from_source' => [
                'required',
                Rule::in([
                    Member::FROM_SOURCE_JOY_BOOKING,
                    Member::FROM_SOURCE_TWDD,
                    Member::FROM_SOURCE_JOYMAP,
                    Member::FROM_SOURCE_RESTAURANT_BOOKING,
                    Member::FROM_SOURCE_JOYMAP_APP,
                    Member::FROM_SOURCE_TW_AUTHORIZATION,
                    Member::FROM_SOURCE_GOOGLE_BOOKING,
                ]),
            ],
            'email' => 'nullable|email',
            'gender' => [
                'nullable',
                Rule::in([
                    Member::GENDER_FEMALE,
                    Member::GENDER_MALE,
                    Member::GENDER_UNKNOWN,
                ]),
            ],
            'avatar' => 'nullable|url',
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
