<?php

use Mtsung\JoymapCore\Models\Member;

return [
    'status' => [
        Member::STATUS_SUSPENDED => '停權',
        Member::STATUS_NORMAL => '正常',
        Member::STATUS_FREEZE => '凍結',
    ],
    'is_joy_fan' => [
        Member::IS_JOY_FAN_NOT_ACTIVATED => '未開通',
        Member::IS_JOY_FAN_ACTIVATED => '已開通',
    ],
    'from_source' => [
        Member::FROM_SOURCE_JOY_BOOKING => 'JoyBooking',
        Member::FROM_SOURCE_TWDD => 'TWDD',
        Member::FROM_SOURCE_JOYMAP => 'Joymap',
        Member::FROM_SOURCE_RESTAURANT_BOOKING => '餐廳代客訂位',
        Member::FROM_SOURCE_JOYMAP_APP => 'Joymap_APP',
        Member::FROM_SOURCE_TW_AUTHORIZATION => 'TW 授權',
        Member::FROM_SOURCE_GOOGLE_BOOKING => 'Google訂位',
    ],
    'is_foreigner' => [
        Member::IS_FOREIGNER_LOCAL => '本國人',
        Member::IS_FOREIGNER_FOREIGN => '外國人',
    ]
];
