<?php

use Mtsung\JoymapCore\Models\Lottery;

return [
    'type' => [
        Lottery::TYPE_COMMON_TURNTABLE => '一般轉盤',
        Lottery::TYPE_SPECIAL_TURNTABLE => '黃金轉盤',
        Lottery::TYPE_COMMON_GACHA => '一般扭蛋',
        Lottery::TYPE_SPECIAL_GACHA => '黃金扭蛋'
    ],
];