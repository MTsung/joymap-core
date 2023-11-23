<?php

use Mtsung\JoymapCore\Models\MemberChargePlan;

return [
    'status' => [
        MemberChargePlan::STATUS_PENDING => '未處理',
        MemberChargePlan::STATUS_IN_TRANSIT => '已寄送',
        MemberChargePlan::STATUS_CONFIRMED => '已確認',
    ]
];
