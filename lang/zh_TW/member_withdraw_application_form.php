<?php

use Mtsung\JoymapCore\Models\MemberWithdrawApplicationForm;

return [
    'is_active' => [
        MemberWithdrawApplicationForm::IS_ACTIVE_OFF => '審查中',
        MemberWithdrawApplicationForm::IS_ACTIVE_ON => '審查成功',
    ],
];
