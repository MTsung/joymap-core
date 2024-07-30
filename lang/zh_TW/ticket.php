<?php

use Mtsung\JoymapCore\Models\Ticket;
use Mtsung\JoymapCore\Models\TicketBatch;
use Mtsung\JoymapCore\Models\TicketBrand;
use Mtsung\JoymapCore\Models\TicketNumber;
use Mtsung\JoymapCore\Models\TicketNumberLog;

return [
    'type' => [
        Ticket::TYPE_EXCHANGE => '商品兌換',
        Ticket::TYPE_BALANCE => '餘額劵',
    ],
    'brand_source' => [
        TicketBrand::SOURCE_QWARE => '安源資訊',
        TicketBrand::SOURCE_MOHIST => '墨攻網路',
    ],
    'number_status' => [
        TicketNumber::STATUS_INIT => '初始',
        TicketNumber::STATUS_USABLE => '可以使用',
        TicketNumber::STATUS_REDEEMED => '已核銷',
        TicketNumber::STATUS_EXPIRED => '已過期',
        TicketNumber::STATUS_INVALID => '已作廢',
    ],
    'batch_status' => [
        TicketBatch::STATUS_UPLOADING => '上傳中',
        TicketBatch::STATUS_SUCCESS => '成功',
        TicketBatch::STATUS_FAILURE => '失敗',
    ],
    'number_log_status' => [
        TicketNumberLog::ACTION_GIVED => '發放',
        TicketNumberLog::ACTION_CANCEL_GIVED => '取消發放',
        TicketNumberLog::ACTION_REDEEMED => '核銷',
        TicketNumberLog::ACTION_CANCEL_REDEEMED => '取消核銷',
        TicketNumberLog::ACTION_EXPIRED => '過期',
        TicketNumberLog::ACTION_INVALID => '失效作廢',
    ]
];
