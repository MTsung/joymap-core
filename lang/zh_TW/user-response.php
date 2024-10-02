<?php

use Mtsung\JoymapCore\Models\UserResponse;

return [
    'status' => [
        UserResponse::STATUS_GENERAL_REPORT => '一般建議回報',
        UserResponse::STATUS_SERVICE_REPORT => '店家服務回報',
        UserResponse::STATUS_SYSTEM_ABNORMALITY => '系統異常',
    ],
    'processing_status' => [
        UserResponse::PROCESSING_STATUS_PENDING => '未處理',
        UserResponse::PROCESSING_STATUS_IN_PROGRESS => '進行中',
        UserResponse::PROCESSING_STATUS_CLOSED => '已結案',
    ],
];
