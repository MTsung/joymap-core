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
    // 兌換比為樂粉現金回饋：享樂幣
    'money_fee' => env('MONEY_FEE', 1),
    'coin_fee' => env('COIN_FEE', 5),
    // 每月分潤獎金兌換享樂幣上限
    'per_month_bonus_change_max' => env('PER_MONTH_BONUS_CHANGE_MAX', 10000),
    // 分潤比例
    'bonus_percent_array' => json_decode(env('BONUS_PERCENT_ARRAY', '[0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1]'), true),
    // 店家推薦獎勵比例
    'recommend_store_bonus_percent' => env('RECOMMEND_STORE_BONUS_PERCENT', 0.001),
    // 天使點數兌換現金匯率 1:POINT_CHANGE_MONEY_FEE
    'point_change_money_fee' => env('POINT_CHANGE_MONEY_FEE', 300),

    // 訂閱一期計算天數
    'subscription_period_days' => env('SUBSCRIPTION_PERIOD_DAYS', 30),
    // 訂閱十二期計算天數
    'subscription_period_year' => env('SUBSCRIPTION_PERIOD_YEAR', 365),
    // 訂閱額外分潤比例
    'subscription_bonus_percent' => env('SUBSCRIPTION_BONUS_PERCENT', 0.18),
    // 訂閱額外分潤金額上限
    'subscription_bonus_amount_max' => env('SUBSCRIPTION_BONUS_AMOUNT_MAX', 1000),
    // 訂閱邀請分紅點數
    'subscription_invite_point' => env('SUBSCRIPTION_INVITE_POINT', 5),
    // 訂閱額外分潤比例
    'subscription_bonus_percent_array' => json_decode(env('SUBSCRIPTION_BONUS_PERCENT_ARRAY', '[15, 15]'), true),
    // 訂閱額外加碼任務計算開始時間
    'subscription_bonus_task_start_at' => env('SUBSCRIPTION_BONUS_TASK_START_AT', '2024-05-20'),

    // 首月訂閱免費開始時間
    'subscription_free_event_start_at' => env('SUBSCRIPTION_FREE_EVENT_START_AT', '2024-06-06'),
    // 首月訂閱免費數量限制
    'subscription_free_event_limit' => env('SUBSCRIPTION_FREE_EVENT_LIMIT', 200),

    //抽獎活動起算時間
    'lottery_activity_start_at' => env('LOTTERY_ACTIVITY_START_AT', '2024-07-01'),

    // 每月任務(普通刮刮樂):邀請好友註冊達標數
    'scratch_card_monthly_invite_register_target' => env('SCRATCH_CARD_MONTHLY_INVITE_REGISTER_TARGET', 20),

    // 每月任務(普通刮刮樂):邀請VIP達標數
    'scratch_card_monthly_invite_vip_target' => env('SCRATCH_CARD_MONTHLY_INVITE_VIP_TARGET', 5),

    // 任務(黃金刮刮樂):歷史邀請VIP達標數
    'scratch_card_history_invite_vip_target' => env('SCRATCH_CARD_HISTORY_INVITE_VIP_TARGET', 10),
];
