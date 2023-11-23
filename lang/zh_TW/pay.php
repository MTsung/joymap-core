<?php

use Mtsung\JoymapCore\Models\PayLog;

return [
    'from_source' => [
        PayLog::FROM_SOURCE_JOYMAP => 'Joymap',
        PayLog::FROM_SOURCE_TWDD => 'TWDD',
    ],
    'discount_type' => [
        PayLog::DISCOUNT_TYPE_DEFAULT => '-',
        PayLog::DISCOUNT_TYPE_JCOIN => 'Jcoin',
        PayLog::DISCOUNT_TYPE_COUPON => '經銷券',
    ],
];
