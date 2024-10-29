<?php

return [
    'default' => env('DESIGNATED_DRIVER_CHANNEL', 'twdd'),

    'channels' => [
        'twdd' => [
            'url' => env('DESIGNATED_DRIVER_TWDD_URL', 'https://api-test.twdd.tw/vendor/v2/line-go/'),
            'user' => env('DESIGNATED_DRIVER_TWDD_USER'),
            'pw' => env('DESIGNATED_DRIVER_TWDD_PW'),
            'callback_url' => env('DESIGNATED_DRIVER_TWDD_CALLBACK_URL', 'https://shopapi-test.joymap.tw/callback/twdd'),
        ],
    ],
];
