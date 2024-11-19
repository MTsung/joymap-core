<?php

return [
    'default' => env('SMS_CHANNEL', 'infobip'),

    'channels' => [
        'infobip' => [
            'url' => env('INFOBIP_URL', 'https://39wzv.api.infobip.com/sms/2/text/advanced'),
            'api_key' => env('INFOBIP_KEY', ''),
            'from' => env('INFOBIP_FROM', 'JOYMAP'),
        ],
        'mitake' => [
            'url' => env('MITAKE_URL', 'https://smsapi.mitake.com.tw/api/mtk/SmSend?CharsetURL=UTF-8'),
            'username' => env('MITAKE_USERNAME', ''),
            'password' => env('MITAKE_PASSWORD', ''),
        ],
    ],
];
