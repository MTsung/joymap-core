<?php

use Mtsung\JoymapCore\Models\Broadcast;

return [
    'status' => [
        Broadcast::STATUS_DRAFT => '草稿',
        Broadcast::STATUS_ONLINE => '上線',
    ],
    'broadcast_status' => [
        Broadcast::BROADCAST_STATUS_CANCELLED => '已取消',
        Broadcast::BROADCAST_STATUS_SENT => '已發送',
        Broadcast::BROADCAST_STATUS_PENDING => '待發送',
        Broadcast::BROADCAST_STATUS_SENDING => '發送中',
    ],
    'broadcast_target' => [
        'all' => '全體會員',
    ],
];
