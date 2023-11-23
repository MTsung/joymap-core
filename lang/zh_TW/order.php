<?php

use Mtsung\JoymapCore\Models\Order;

return [
    'status' => [
        Order::STATUS_CANCEL_BY_USER => '取消訂位',
        Order::STATUS_SUCCESS_BOOKING_BY_USER => '成功訂位',
        Order::STATUS_SUCCESS_BOOKING_BY_STORE => '餐廳成功訂位',
        Order::STATUS_CANCEL_BY_STORE => '餐廳取消訂位',
        Order::STATUS_RESERVED_SEAT => '保留座位',
        Order::STATUS_SEATED => '已入座',
        Order::STATUS_LEFT_SEAT => '已離席',
        Order::STATUS_NO_SHOW => '未出席'
    ],
    'from_source' => [
        Order::FROM_SOURCE_JOY_BOOKING => 'JoyBooking',
        Order::FROM_SOURCE_TWDD => 'TWDD',
        Order::FROM_SOURCE_JOYMAP => 'Joymap',
        Order::FROM_SOURCE_RESTAURANT_BOOKING => '餐廳代客訂位',
        Order::FROM_SOURCE_JOYMAP_APP => 'Joymap_APP',
        Order::FROM_SOURCE_TW_AUTHORIZATION => 'TW 授權',
        Order::FROM_SOURCE_GOOGLE_BOOKING => 'Google訂位',
    ],
    //訂單Log 操作者
    'order_time_log_create_by' => [
        'order_time' => '會員',
        'store_order_time' => '店家',
        'cancel_time' => '會員',
        'store_cancel_time' => '店家',
        'seat_time' => '店家',
        'system_seat_time' => '系統人員',
        'seat_hold_time' => '會員',
        'bill_time' => '店家',
        'system_bill_time' => '系統人員',
        'no_show_time' => '系統自動',
        'comment_time' => '會員',
    ],
    //訂單Log 項目名稱
    'order_time_log_name' => [
        'order_time' => '新增訂位',
        'store_order_time' => '新增訂位',
        'cancel_time' => '會員取消',
        'store_cancel_time' => '店家取消',
        'seat_time' => '店家轉入座',
        'system_seat_time' => '系統轉入座',
        'seat_hold_time' => '確認保留',
        'bill_time' => '店家轉結帳',
        'system_bill_time' => '系統轉結帳',
        'no_show_time' => 'No-Show',
        'comment_time' => '留下評論',
    ],
];
