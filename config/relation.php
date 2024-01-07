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

    'joy_fans' => [
        // 分潤階層上限
        'share_profit_level_max' => env('JOY_FANS_SHARE_PROFIT_LEVEL_MAX', 4),
        // 分潤獎金上限
        'share_profit_bonus_max' => env('JOY_FANS_SHARE_PROFIT_BONUS_MAX', 100000),
        // 每月分潤獎金兌換享樂幣上限
        'per_month_share_profit_bonus_change_coin_max' => env('JOY_FANS_PER_MONTH_SHARE_PROFIT_BONUS_CHANGE_COIN_MAX', 3360),
        // 分潤比例
        'share_profit_proportions' => json_decode(env('JOY_FANS_SHARE_PROFIT_PROPORTIONS', '[0.0016,0.0016,0.0012,0.001]'), true)
    ],

    'joy_dealers' => [
        // 分潤階層上限
        'share_profit_level_max' => env('JOY_DEALERS_SHARE_PROFIT_LEVEL_MAX', 7),
        // 分潤獎金上限
        'share_profit_bonus_max' => env('JOY_DEALERS_SHARE_PROFIT_BONUS_MAX', 500000),
        // 分潤獎金上限(有加買權益單位擁有的特權設定)
        'share_profit_bonus_max_3_unit_privilege' => env('JOY_DEALERS_SHARE_PROFIT_BONUS_MAX_3_UNIT_PRIVILEGE', 550000),
        // 分潤獎金上限(有加買權益單位擁有的特權設定)
        'share_profit_bonus_max_5_unit_privilege' => env('JOY_DEALERS_SHARE_PROFIT_BONUS_MAX_5_UNIT_PRIVILEGE', 600000),
        // 每月分潤獎金兌換享樂幣上限
        'per_month_share_profit_bonus_change_coin_max' => env('JOY_DEALERS_PER_MONTH_SHARE_PROFIT_BONUS_CHANGE_COIN_MAX', 11376),
        // 每月分潤獎金兌換享樂幣上限(有加買權益單位擁有的特權設定)
        'per_month_share_profit_bonus_change_coin_max_privilege' => env('JOY_DEALERS_PER_MONTH_SHARE_PROFIT_BONUS_CHANGE_COIN_MAX_PRIVILEGE', 13360),
        // 分潤比例
        'share_profit_proportions' => json_decode(env('JOY_DEALERS_SHARE_PROFIT_PROPORTIONS', '[0.0016,0.0016,0.0012,0.001,0.0006,0.0004,0.0002]'), true),
        // 分紅階層上限
        'share_dividend_point_level_max' => env('JOY_DEALERS_SHARE_DIVIDEND_POINT_LEVEL_MAX', 5),
        // 分紅點數比例
        'share_dividend_points' => json_decode(env('JOY_DEALERS_SHARE_DIVIDEND_POINTS', '[7,5,3,2,1]'), true),
        // 檔期規則
        'specify_give_backs' => json_decode(env('JOY_DEALERS_SPECIFY_GIVE_BACKS', '[]'), true),
        // 點數兌換現金匯率
        'point_change_money_fee' => env('JOY_DEALERS_POINT_CHANGE_MONEY_FEE', 300),
        // 發送點數天數
        'send_point_sub_day' => env('JOY_DEALERS_SEND_POINT_SUB_DAY', 31),
        // 推薦店家分潤獎金比例
        'recommend_store_bonus_percent' => env('JOY_DEALERS_RECOMMEND_STORE_BONUS_PERCENT', 0.001),
    ],
];
