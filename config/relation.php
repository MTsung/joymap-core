<?php

return [
    // 階層最大會員數量
    'level_member_count_max' => env('LEVEL_MEMBER_COUNT_MAX', 10),
    // 獲得回饋最小累計付款金額
    'bonus_min_amount' => env('BONUS_MIN_AMOUNT', 1000),
    // 提領手續費
    'withdraw_hand_fee' => env('HAND_FEE', 30),
    // 最低提款金額
    'min_money_withdraw' => env('MIN_MONEY_WITHDRAW', 200),
    // 分潤獎金上限
    'bonus_max' => env('BONUS_MAX', 510000),
    // 兌換比為樂粉現金回饋：享樂幣
    'money_fee' => env('MONEY_FEE', 1),
    'coin_fee' => env('COIN_FEE', 5),
    // 每月分潤獎金兌換享樂幣上限
    'per_month_bonus_change_max' => env('PER_MONTH_BONUS_CHANGE_MAX', 10000),
    // 分潤比例
    'bonus_percent_array' => json_decode(env('BONUS_PERCENT_ARRAY', '[0.0016,0.0016,0.0012,0.001,0.0006,0.0004,0.0002]'), true),
    // 店家推薦獎勵比例
    'recommend_store_bonus_percent' => env('RECOMMEND_STORE_BONUS_PERCENT', 0.001),
    // 天使點數兌換現金匯率 1:POINT_CHANGE_MONEY_FEE
    'point_change_money_fee' => env('POINT_CHANGE_MONEY_FEE', 300),
];
