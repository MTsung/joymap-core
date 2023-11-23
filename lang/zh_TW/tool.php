<?php

use Mtsung\JoymapCore\Models\Article;
use Mtsung\JoymapCore\Models\Carousel;
use Mtsung\JoymapCore\Models\Notification;

return [
    'article' => [
        'is_hot' => [
            Article::IS_HOT_NO => '否',
            Article::IS_HOT_YES => '是',
        ],
        'status' => [
            Article::STATUS_OFF_SHELF => '下架',
            Article::STATUS_ON_SHELF => '上架',
            Article::STATUS_DRAFT => '草稿',
        ],
    ],
    'carousel' => [
        'status' => [
            Notification::STATUS_OFF_SHELF => '下架',
            Notification::STATUS_ON_SHELF => '上架',
        ],
    ],
    'notification' => [
        'status' => [
            Carousel::STATUS_OFF_SHELF => '下架',
            Carousel::STATUS_ON_SHELF => '上架',
        ],
    ],
];
