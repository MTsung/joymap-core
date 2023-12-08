<?php

namespace Mtsung\JoymapCore\Params\Member;


use Illuminate\Validation\Rule;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Params\Validation;

class MemberRegisterParams extends Validation
{
    public static function rules(): array
    {
        return [
            // 目前只給台灣手機註冊
            'phone' => 'required|regex:/^09[0-9]{8}$/',
            'name' => 'required|string',
            'gender' => [
                'required',
                Rule::in([
                    Member::GENDER_FEMALE,
                    Member::GENDER_MALE,
                ]),
            ],
            'birthday' => 'nullable|date_format:Y-m-d',
            'from_source' => [
                'required',
                Rule::in([
                    Member::FROM_SOURCE_JOYMAP,
                    Member::FROM_SOURCE_JOYMAP_APP,
                    Member::FROM_SOURCE_TW_AUTHORIZATION,
                ]),
            ],
            'register_device' => 'required|string|in:iPhone,Android,PC',
            'type' => 'nullable|string|in:facebook,google,apple',
            'facebook_id' => 'required_if:type,facebook|unique:members,facebook_id',
            'google_id' => 'required_if:type,google|unique:members,google_id',
            'apple_id' => 'required_if:type,apple|unique:members,apple_id',
            'avatar' => 'nullable|url',
            'email' => 'nullable|email',
            'from_invite_code' => 'nullable|string',
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
