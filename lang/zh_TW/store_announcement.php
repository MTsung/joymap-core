<?php

use Mtsung\JoymapCore\Models\StoreAnnouncement;

return [
    'type' => [
        StoreAnnouncement::SEND_TYPE_ALL => '所有店家',
        StoreAnnouncement::SEND_TYPE_TAG => '標籤店家',
        StoreAnnouncement::SEND_TYPE_FOOD_TYPE => '店家類型',
        StoreAnnouncement::SEND_TYPE_ASSIGN => '指定店家',
    ],
];
