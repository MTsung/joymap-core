<?php

use Mtsung\JoymapCore\Models\StoreWalletTransactionRecord;

return [
    'type' => [
        StoreWalletTransactionRecord::TYPE_DISCOUNT => '享樂折抵',
        StoreWalletTransactionRecord::TYPE_STORE_WITHDRAW => '商家提領',
        StoreWalletTransactionRecord::TYPE_PLATFORM_COMMISSION => '平台抽成',
        StoreWalletTransactionRecord::TYPE_REFUND => '退刷回收',
        StoreWalletTransactionRecord::TYPE_OTHER_CHANGES => '其他異動',
        StoreWalletTransactionRecord::TYPE_ACTIVITY_FEEDBACK => '活動回饋',
        StoreWalletTransactionRecord::TYPE_DRIVER_FEEDBACK => '代駕回饋金',
    ],
    'status' => [
        StoreWalletTransactionRecord::STATUS_PROCESSING => '處理中',
        StoreWalletTransactionRecord::STATUS_SUCCESS => '處理成功',
        StoreWalletTransactionRecord::STATUS_FAILURE => '處理失敗',
    ],
];
