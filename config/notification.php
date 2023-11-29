<?php

return [
    'default' => env('NOTIFICATION_CHANNEL', 'fcm'),

    'channels' => [
        'fcm' => [
            'url' => env('FCM_URL', 'https://fcm.googleapis.com/fcm/send'),
            'token' => env('FCM_KEY', ''),
        ],
        'gorush' => [
            'host' => env('GORUSH_HOST', 'http://localhost'),
            'port' => env('GORUSH_PORT', 9999),
            'path' => env('GORUSH_PATH', '/api/push'),
            'topic' => [
                'member' => env('GORUSH_TOPIC_MEMBER', ''),
                'store' => env('GORUSH_TOPIC_STORE', 'com.ChyunYueh.JoyShop'),
            ],
        ],
    ],
];
