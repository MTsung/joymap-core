<?php

use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\StoreNotification;

return [
    'store_notification' => [
        'order' => [
            'success' => [
                Order::FROM_SOURCE_JOY_BOOKING => StoreNotification::STATUS_ORDER_FROM_JOY_BOOKING,
                Order::FROM_SOURCE_TWDD => StoreNotification::STATUS_ORDER_FROM_TWDD,
                Order::FROM_SOURCE_JOYMAP => StoreNotification::STATUS_ORDER_FROM_JOYMAP,
                Order::FROM_SOURCE_RESTAURANT_BOOKING => StoreNotification::STATUS_ORDER_FROM_STORE,
                Order::FROM_SOURCE_JOYMAP_APP => StoreNotification::STATUS_ORDER_FROM_JOYMAP,
                Order::FROM_SOURCE_GOOGLE_BOOKING => StoreNotification::STATUS_ORDER_FROM_GOOGLE,
            ],
        ],
    ],
];
