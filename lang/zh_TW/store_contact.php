<?php

use Mtsung\JoymapCore\Models\StoreContact;

return [
    'processing_status' => [
        StoreContact::PROCESSING_STATUS_PENDING => '未處理',
        StoreContact::PROCESSING_STATUS_IN_PROGRESS => '進行中',
        StoreContact::PROCESSING_STATUS_CLOSED => '已結案',
    ],
];
