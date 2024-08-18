<?php

use Mtsung\JoymapCore\Models\Store;

return [
    'can_pay' => [
        Store::CAN_PAY_DISABLED => '停用',
        Store::CAN_PAY_ENABLED => '啟用',
        Store::CAN_PAY_NOT_ENABLED => '未啟用',
        Store::CAN_PAY_NOT_COOPERATING => '-',
    ],
    'is_bank_check' => [
        Store::IS_BANK_CHECK_DEFAULT => '未審核',
        Store::IS_BANK_CHECK_UNDER_REVIEW => '審核中',
        Store::IS_BANK_CHECK_PASSED => '審核通過',
        Store::IS_BANK_CHECK_FAILED_REVIEW => '審核失敗',
    ],
    'can_order' => [
        Store::CAN_ORDER_DISABLED => '停用',
        Store::CAN_ORDER_ENABLED => '啟用',
        Store::CAN_ORDER_NOT_ENABLED => '未啟用',
        Store::CAN_ORDER_NOT_COOPERATING => '-',
    ],
    'can_comment' => [
        Store::CAN_COMMENT_DISABLED => '停用',
        Store::CAN_COMMENT_ENABLED => '啟用',
    ],
    'status' => [
        Store::STATUS_OFF_SHELF => '下架',
        Store::STATUS_ON_SHELF => '上架',
        Store::STATUS_PENDING => '待上架',
    ],
    'is_active' => [
        Store::IS_ACTIVE_CLOSED => '歇業',
        Store::IS_ACTIVE_OPEN => '營業',
    ],
    'from_source' => [
        Store::FROM_SOURCE_JOYMAP => 'Joymap',
        Store::FROM_SOURCE_TWDD => 'TWDD',
    ],
    'business_status_now' => [
        '0' => '公休',
        '1' => '營業中',
        '2' => '休息中',
    ],
];
