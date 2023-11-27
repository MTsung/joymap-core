<?php

return [
    'channels' => [
        'hitrust-pay' => [
            'driver' => 'daily',
            'path' => storage_path('logs/hitrust/pay.log'),
            'days' => 90,
        ],
        'spgateway-pay' => [
            'driver' => 'daily',
            'path' => storage_path('logs/spgateway/pay.log'),
            'days' => 90,
        ],
        'spgateway-store' => [
            'driver' => 'daily',
            'path' => storage_path('logs/spgateway/store.log'),
            'days' => 90,
        ],
        'fcm' => [
            'driver' => 'daily',
            'path' => storage_path('logs/fcm.log'),
            'days' => 14,
        ],
        'gorush' => [
            'driver' => 'daily',
            'path' => storage_path('logs/gorush.log'),
            'days' => 14,
        ],
    ],
];
