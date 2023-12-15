<?php

use Mtsung\JoymapCore\Models\NotificationOrder;

return [
    'notification_title' => [
        NotificationOrder::STATUS_USER_SUCCESS => '您已完成 :store_name 的訂位!',
        NotificationOrder::STATUS_STORE_SUCCESS => ':store_name 已幫您完成訂位!',
        NotificationOrder::STATUS_MODIFY => '您在 :store_name 的訂位已被修改!',
        NotificationOrder::STATUS_USER_CANCEL => '您已取消 :store_name 的訂位!',
        NotificationOrder::STATUS_STORE_CANCEL => '您在 :store_name 的訂位已被取消!',
        NotificationOrder::STATUS_REMINDER => '提醒您，明天有 :store_name 的訂位，您是否保留?',
        NotificationOrder::STATUS_REMINDER_NO_BUTTON => '提醒您，明天有 :store_name 的訂位，您是否保留?',
        NotificationOrder::STATUS_SEATED => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        NotificationOrder::STATUS_SEATED_NO_BUTTON => '感謝您的光臨，:store_name，邀請您留下用餐評論'
    ],
    'notification_body' => '訂位時間(:datetime) • :people位',
];
