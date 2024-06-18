<?php

use Mtsung\JoymapCore\Models\LotteryLog;

return [
    'type' => [
        LotteryLog::TYPE_EVERY_DAY_LOGIN => '每日登入',
        LotteryLog::TYPE_PAYMENT_100 => '單筆刷卡100元以上',
        LotteryLog::TYPE_PAYMENT_1000 => '單筆刷卡1000元以上',
        LotteryLog::TYPE_CUMULATIVE_5_TRANSACTIONS => '每累積5筆交易',
        LotteryLog::TYPE_MONTH_CUMULATIVE_3000_AMOUNT => '當月支付累積金額3000元(每月一次)',
        LotteryLog::TYPE_MONTH_INVITE_20_REGISTER => '當月邀請註冊成功20位(每月一次)',
        LotteryLog::TYPE_MONTH_CUMULATIVE_INVITE_5_VIP => '當月累積邀請5位VIP加入(每月一次)',
        LotteryLog::TYPE_INVITE_10_VIP => '每邀請10位VIP加入',
    ],
];