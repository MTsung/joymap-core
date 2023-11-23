<?php

use Mtsung\JoymapCore\Models\MemberDealerBonus;

return [
    'status' => [
        MemberDealerBonus::STATUS_SCHEDULED => '未發放',
        MemberDealerBonus::STATUS_ONGOING => '發送中',
        MemberDealerBonus::STATUS_COMPLETED => '已發放',
        MemberDealerBonus::STATUS_FAILED => '發送失敗',
        MemberDealerBonus::STATUS_NOT_REACHED => '不符資格',
        MemberDealerBonus::STATUS_REFUNDED => '退刷',
        MemberDealerBonus::STATUS_NEGLECT => '忽略',
    ],
];
