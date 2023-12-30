<?php

return [
    'order' => [
        'title' => [
            'success' => [
                'by_user' => '您已完成 :store_name 的訂位!',
                'by_store' => ':store_name 已幫您完成訂位!',
            ],
            'success_to_store' => [
                'by_user' => '您有一筆新的訂位',
                'by_store' => '您建立一筆新訂位',
            ],
            'update' => '您在 :store_name 的訂位已被修改!',
            'cancel' => [
                'by_user' => '您已取消 :store_name 的訂位!',
                'by_store' => '您在 :store_name 的訂位已被取消!',
            ],
            'cancel_to_store' => [
                'by_user' => '您有一筆訂位被取消',
                'by_store' => '您取消了訂位',
            ],
            'remind' => '提醒您，明天有 :store_name 的訂位，您是否保留?',
            'comment_remind' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        ],
        'body' => '訂位時間(:datetime) • :people位',
        'body_to_store' => ':name:gender．:date :week :time．:adult_num大:child_num小',
    ],
    'pay' => [
        'title' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        'body' => '支付時間(:datetime) • 金額 :amount',
    ],
    'pay_to_store' => [
        'title' => '您有一筆新的支付款項',
        'body' => '單號為 :pay_no 金額為 :amount',
    ],
    'comment' => [
        'title' => '您有一則新的評論',
        'body' => ':body',
    ],
];
