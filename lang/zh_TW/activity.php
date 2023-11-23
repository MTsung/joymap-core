<?php

use Mtsung\JoymapCore\Models\Activity;

return [
    'status' => [
        Activity::STATUS_CANCELLED => '已取消',
        Activity::STATUS_IN_PROGRESS => '執行中',
        Activity::STATUS_PENDING => '待執行',
        Activity::STATUS_FINISHED => '已結束',
    ],
    'extra_type' => [
        Activity::EXTRA_TYPE_PERCENTAGE => '刷卡金額百分比',
        Activity::EXTRA_TYPE_AMOUNT => '固定享樂幣',
    ],
    'store_type' => [
        Activity::STORE_TYPE_SPECIFIC => '指定',
        Activity::STORE_TYPE_ALL => '全部',
    ],
    'extra_restriction_type' => [
        Activity::EXTRA_RESTRICTION_TYPE_NONE => '不限制',
        Activity::EXTRA_RESTRICTION_TYPE_SINGLE_STORE => '單店',
        Activity::EXTRA_RESTRICTION_TYPE_TOTAL_AMOUNT => '總額',
    ],
    'extra_coin_expire_day' => [
        '30' => '1個月',
        '60' => '2個月',
        '90' => '3個月',
        '120' => '4個月',
        '150' => '5個月',
        '180' => '6個月',
        '210' => '7個月',
        '240' => '8個月',
        '270' => '9個月',
        '300' => '10個月',
        '330' => '11個月',
        '360' => '1年',
        '720' => '2年',
    ],
];
