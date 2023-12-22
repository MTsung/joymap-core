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
            'path' => storage_path('logs/notification/fcm.log'),
            'days' => 14,
        ],
        'gorush' => [
            'driver' => 'daily',
            'path' => storage_path('logs/notification/gorush.log'),
            'days' => 14,
        ],
        'infobip' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sms/infobip.log'),
            'days' => 14,
        ],
        'jcoin' => [
            'driver' => 'daily',
            'path' => storage_path('logs/jcoin/jcoin.log'),
            'days' => 14,
        ],
        'mail' => [
            'driver' => 'daily',
            'path' => storage_path('logs/mail/mail.log'),
            'days' => 14,
        ],
        'can_order_time' => [
            'driver' => 'daily',
            'path' => storage_path('logs/can_order_time/can_order_time.log'),
            'days' => 14,
        ],
        'refund' => [
            'driver' => 'daily',
            'path' => storage_path('logs/pay/refund.log'),
            'days' => 365,
        ],
    ],
];
