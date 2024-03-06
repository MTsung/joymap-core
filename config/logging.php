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
        'fcm-v1' => [
            'driver' => 'daily',
            'path' => storage_path('logs/notification/fcm_v1.log'),
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
        'subscription' => [
            'driver' => 'daily',
            'path' => storage_path('logs/subscription/subscription.log'),
            'days' => 365,
        ],
        'request' => [
            'driver' => 'daily',
            'path' => storage_path('logs/request/request.log'),
            'days' => 14,
        ],
        'slow_request' => [
            'driver' => 'daily',
            'path' => storage_path('logs/request/slow_request.log'),
            'days' => 90,
        ],
    ],
];
