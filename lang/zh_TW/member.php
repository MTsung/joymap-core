<?php

use Mtsung\JoymapCore\Models\Member;

return [
    'is_active' => [
        Member::PHONE_IS_INACTIVE => '未驗證',
        Member::PHONE_IS_ACTIVE => '已驗證',
    ],
    'is_email_active' => [
        Member::EMAIL_IS_INACTIVE => '未驗證',
        Member::EMAIL_IS_ACTIVE => '已驗證',
    ],
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
        Member::FROM_SOURCE_RESTAURANT_BOOKING => '店家代客訂位',
        Member::FROM_SOURCE_JOYMAP_APP => 'Joymap_APP',
        Member::FROM_SOURCE_TW_AUTHORIZATION => 'TW 授權',
        Member::FROM_SOURCE_GOOGLE_BOOKING => 'Google訂位',
    ],
    'is_foreigner' => [
        Member::IS_FOREIGNER_LOCAL => '本國人',
        Member::IS_FOREIGNER_FOREIGN => '外國人',
    ],
    'message' => [
        'permission' => [
            'attempt_fail' => '帳號或密碼錯誤',
            'no_login' => '請登入',
            'expired' => '請重新登入',
            'denied' => '權限不足',
            'account_is_deleted' => '您的帳號已刪除，如有疑問請洽客服',
            'account_is_freeze' => '您的帳號尚在保護中，如有疑問請洽客服',
            'phone_inactive' => '手機號碼尚未驗證',
        ],
        'register' => [
            'phone_existed' => '該手機已註冊過，無法重新註冊，如有疑問請洽客服',
            'account_is_freeze' => '您的帳號尚在保護中，無法重新註冊，如有疑問請洽客服',
        ],
        'invite' => [
            'not_existed' => '此邀請碼不存在',
            'not_member_dealer' => '無效邀請碼，請再次確認',
        ],
    ],
];
