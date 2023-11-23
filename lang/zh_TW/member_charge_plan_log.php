<?php

use Mtsung\JoymapCore\Models\MemberChargePlanLog;

return [
    'type' => [
        MemberChargePlanLog::TYPE_APPLY_TIME => '申請',
        MemberChargePlanLog::TYPE_SHIPPING_TIME => '寄送合約',
        MemberChargePlanLog::TYPE_CONFIRM_TIME => '確認開通',
    ]
];
