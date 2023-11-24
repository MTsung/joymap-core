<?php

return [
    'line_notify' => [
        'enable' => env('SEND_ERROR_LOG_LINE', false,),
        'url' => 'https://notify-api.line.me/api/notify',
        'token' => env('LINE_NOTIFY_TOKEN', ''),
    ],
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
];
