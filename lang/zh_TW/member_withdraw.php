<?php

use Mtsung\JoymapCore\Models\MemberWithdraw;

return [
    'status' => [
        MemberWithdraw::STATUS_PENDING => '待處理',
        MemberWithdraw::STATUS_COMPLETED => '匯款完成',
    ],
];
