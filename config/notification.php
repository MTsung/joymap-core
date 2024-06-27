<?php

return [
    'default' => env('NOTIFICATION_CHANNEL', 'fcm_v1'),

    'channels' => [
        'fcm' => [
            'url' => env('FCM_URL', 'https://fcm.googleapis.com/fcm/send'),
            'token' => env('FCM_KEY', ''),
        ],
        'fcm_v1' => [
            'url' => env('FCM_V1_URL', 'https://fcm.googleapis.com/v1/projects/joymap-android/messages:send'),
            'topic_url' => env('FCM_V1_TOPIC_URL', 'https://iid.googleapis.com/iid/v1:batchAdd'),
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

    'lottery_first_draw_reminder_chunk' => env('LOTTERY_FIRST_DRAW_REMINDER_CHUNK', 1000),
];
