<?php

use Mtsung\JoymapCore\Models\CoinLog;
use Mtsung\JoymapCore\Models\JcCoin;
use Mtsung\JoymapCore\Models\JcTransaction;

return [
    'type' => [
        CoinLog::TYPE_CONSUME_REWARD => '消費回饋',
        CoinLog::TYPE_CONSUME_DISCOUNT => '消費折抵',
        CoinLog::TYPE_SYSTEM_RECLAIM => '系統回收',
        CoinLog::TYPE_SYSTEM_COMPENSATION => '系統回補',
        CoinLog::TYPE_SYSTEM_TASK => '系統任務',
        CoinLog::TYPE_ACTIVITY_BONUS => '活動加碼',
        CoinLog::TYPE_JOY_FAN_REWARD => '天使紅利',
        CoinLog::TYPE_WITHDRAW_FUN_REWARD => '提領天使紅利',
        CoinLog::TYPE_ACTIVITY_TASK => '活動任務',
    ],
    'jc_transactions_type' => [
        JcCoin::TYPE_GIVE => '儲值',
        JcCoin::TYPE_USE => '消費',
        JcCoin::TYPE_EXPIRE => '過期',
    ],
    'from_source' => [
        CoinLog::FROM_SOURCE_JOYMAP => 'Joymap',
        CoinLog::FROM_SOURCE_TWDD => 'TWDD',
    ],
    'from_partner' => [
        JcCoin::FROM_PARTNER_JOYMAP => 'Joymap',
        JcCoin::FROM_PARTNER_TWDD => 'TWDD',
    ],
    'status' => [
        JcTransaction::STATUS_FAILURE => '失敗',
        JcTransaction::STATUS_SUCCESS => '成功',
        JcTransaction::STATUS_MANUAL_RESEND => '手動補發',
    ],
    'bonus_comment' => '天使紅利：:year年 :month月',
];
