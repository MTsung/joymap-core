<?php

use Mtsung\JoymapCore\Models\UserResponse;

return [
    'status' => [
        UserResponse::STATUS_GENERAL_REPORT => '一般建議回報',
        UserResponse::STATUS_SERVICE_REPORT => '店家服務回報',
        UserResponse::STATUS_SYSTEM_ABNORMALITY => '系統異常',
    ]
];
