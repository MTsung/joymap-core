<?php

return [
    'default' => env('SMS_CHANNEL', 'infobip'),

    'channels' => [
        'infobip' => [
            'url' => env('INFOBIP_URL', 'https://39wzv.api.infobip.com/sms/2/text/advanced'),
            'api_key' => env('INFOBIP_KEY', ''),
            'from' => env('INFOBIP_FROM', 'JOYMAP'),
        ],
    ],
];
