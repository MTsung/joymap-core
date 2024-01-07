<?php

use Mtsung\JoymapCore\Models\StoreWalletTransactionRecord;
use Mtsung\JoymapCore\Models\StoreWalletWithdraw;

return [
    'transaction_type' => [
        StoreWalletTransactionRecord::TYPE_DISCOUNT => '1. 享樂折抵',
        StoreWalletTransactionRecord::TYPE_STORE_WITHDRAW => '2. 商家提領',
        StoreWalletTransactionRecord::TYPE_PLATFORM_COMMISSION => '3. 平台抽成',
        StoreWalletTransactionRecord::TYPE_REFUND => '4. 退刷回收',
        StoreWalletTransactionRecord::TYPE_OTHER_CHANGES => '5. 其他異動',
        StoreWalletTransactionRecord::TYPE_ACTIVITY_FEEDBACK => '6. 活動回饋',
        StoreWalletTransactionRecord::TYPE_DRIVER_FEEDBACK => '7. 代駕回饋金',
    ],
    'transaction_status' => [
        StoreWalletTransactionRecord::STATUS_PROCESSING => '處理中',
        StoreWalletTransactionRecord::STATUS_SUCCESS => '成功',
        StoreWalletTransactionRecord::STATUS_FAILURE => '失敗',
    ],
    'withdraw_status' => [
        StoreWalletWithdraw::STATUS_PENDING => '待處理',
        StoreWalletWithdraw::STATUS_COMPLETED => '匯款完成',
    ],
];
