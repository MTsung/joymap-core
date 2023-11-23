<?php

use Mtsung\JoymapCore\Models\MemberBonus;

return [
    'status' => [
        MemberBonus::STATUS_SCHEDULED => '排程中',
        MemberBonus::STATUS_ONGOING => '發放中',
        MemberBonus::STATUS_COMPLETED => '已發放',
        MemberBonus::STATUS_FAILED => '失敗',
        MemberBonus::STATUS_NOT_REACHED => '未達標',
        MemberBonus::STATUS_REFUNDED => '退刷',
    ]
];
