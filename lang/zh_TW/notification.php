<?php

use Mtsung\JoymapCore\Models\NotificationMemberWithdraw;

return [
    'order' => [
        'title' => [
            'success' => [
                'by_user' => '您已完成 :store_name 的訂位!',
                'by_store' => ':store_name 已幫您完成訂位!',
            ],
            'success_to_store' => [
                'by_user' => '您有一筆新的訂位',
                'by_store' => '您建立一筆新訂位',
            ],
            'update' => '您在 :store_name 的訂位已被修改!',
            'cancel' => [
                'by_user' => '您已取消 :store_name 的訂位!',
                'by_store' => '您在 :store_name 的訂位已被取消!',
            ],
            'cancel_to_store' => [
                'by_user' => '您有一筆訂位被取消',
                'by_store' => '您取消了訂位',
            ],
            'remind' => '提醒您，明天有 :store_name 的訂位，您是否保留?',
            'comment_remind' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        ],
        'body' => '訂位時間(:datetime) • :people位',
        'body_to_store' => ':name:gender．:date :week :time．:adult_num大:child_num小',
    ],
    'pay' => [
        'title' => '感謝您的光臨，:store_name，邀請您留下用餐評論',
        'title_no_comment' => '感謝您的光臨，:store_name',
        'body' => '支付時間(:datetime) • 金額 :amount',
    ],
    'pay_to_store' => [
        'title' => '您有一筆新的支付款項',
        'body' => '單號為 :pay_no 金額為 :amount',
    ],
    'comment' => [
        'title' => '您有一則新的評論',
        'body' => ':body',
    ],
    'member_withdraw' => [
        'title' => [
            NotificationMemberWithdraw::STATUS_WITHDRAW_APPLY => '已收到您的提領申請',
            NotificationMemberWithdraw::STATUS_REMITTANCE_COMPLETED => '您的提領申請已通過，款項已匯出',
            NotificationMemberWithdraw::STATUS_ESTIMATED_PROFIT => '本月預估可獲得 :coin 樂粉回饋！',
            NotificationMemberWithdraw::STATUS_NOTIFY_REGISTER_NO_PLAN => '【限時免費】開通樂粉回饋，獲取150元享樂幣獎勵(限量供應)！',
            NotificationMemberWithdraw::STATUS_PAID_PLAN_REWARD => '恭喜您，開通樂粉回饋獎勵已入帳！',
            NotificationMemberWithdraw::STATUS_NOTIFY_MEMBER_NO_REWARD => '本月預估可獲得 :coin 樂粉回饋！',
        ],
        'body' => [
            NotificationMemberWithdraw::STATUS_WITHDRAW_APPLY => '嗨！ :name 您好，已收到您的提領申請，我們將盡快為您處理。',
            NotificationMemberWithdraw::STATUS_REMITTANCE_COMPLETED => '嗨！ :name 您好，您提領樂粉回饋申請已通過，我們已將款項匯出，請在下個營業日確認款項。若有任何疑問，請洽客服中心。',
            NotificationMemberWithdraw::STATUS_ESTIMATED_PROFIT => '嗨！ :name 您好，提醒您於本月結束以前透過享樂支付消費 :amount 元(不含享樂折抵)，即可於次月加碼獲得回饋唷！',
            NotificationMemberWithdraw::STATUS_NOTIFY_REGISTER_NO_PLAN => '現在開通樂粉回饋，可獲得 :coin 元享樂幣(限量供應)。開通後，當您邀請的樂粉使用享樂支付時，我們也會提供額外的樂粉回饋給您！',
            NotificationMemberWithdraw::STATUS_PAID_PLAN_REWARD => '歡迎您加入樂粉回饋，已發送 :coin 享樂幣給您，請於期限內使用完畢唷！',
            NotificationMemberWithdraw::STATUS_NOTIFY_MEMBER_NO_REWARD => '嗨！ :name 您好，提醒您開通樂粉回饋，即可獲得回饋資格唷！',
        ],
    ],
    'new_register' => [
        'title' => '恭喜您，獲得新註冊獎勵',
        'body' => '歡迎您加入享樂地圖，已發送 :coin 元享樂幣給您，請於期限內使用完畢唷！',
    ],
];
