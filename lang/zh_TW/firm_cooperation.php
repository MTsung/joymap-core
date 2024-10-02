<?php

use Mtsung\JoymapCore\Models\FirmCooperation;

return [
    'processing_status' => [
        FirmCooperation::PROCESSING_STATUS_PENDING => '未處理',
        FirmCooperation::PROCESSING_STATUS_IN_PROGRESS => '進行中',
        FirmCooperation::PROCESSING_STATUS_CLOSED => '已結案',
    ],
];
