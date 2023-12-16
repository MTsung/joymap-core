<?php

return [
    'order' => [
        'title' => [
            'success' => [
                'by_user' => '您已完成 :store_name 的訂位!',
                'by_store' => ':store_name 已幫您完成訂位!',
            ],
            'update' => '您在 :store_name 的訂位已被修改!',
            'cancel' => [
                'by_user' => '您已取消 :store_name 的訂位!',
                'by_store' => '您在 :store_name 的訂位已被取消!',
            ],
            'remind' => '提醒您，明天有 :store_name 的訂位，您是否保留?',
            'comment_remind' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        ],
        'body' => '訂位時間(:datetime) • :people位',
    ],
    'pay' => [
        'title' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        'body' => '支付時間(:datetime) • 金額 :amount',
    ],
];
