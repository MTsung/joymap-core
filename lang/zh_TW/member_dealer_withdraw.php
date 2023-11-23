<?php

use Mtsung\JoymapCore\Models\MemberDealerPointWithdraw;

return [
    'status' => [
        MemberDealerPointWithdraw::STATUS_UNDER_REVIEW => '待處理',
        MemberDealerPointWithdraw::STATUS_COMPLETE_REMITTANCE => '匯款完成',
    ],
];
