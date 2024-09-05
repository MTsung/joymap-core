<?php

return [
    'default' => env('PAY_CHANNEL', 'spgateway'),

    'channels' => [
        'hitrustpay' => [
            'url' => env(
                'HITRUST_URL',
                'https://testtrustlink.hitrust.com.tw/TrustLink/TrxReqForJava'
            ),
            'referer_url' => env(
                'HITRUST_REFERER_URL',
                'https://member-test.twdd.tw'
            ),
            'callback_url' => env(
                'HITRUST_CALLBACK_URL',
                'https://webapi-test.joymap.tw/credit-card/callback'
            ),
        ],
        // 藍新＆智付通的API 一模一樣 domain 不同而已
        'spgateway' => [
            // 刷卡＆綁卡
            'credit_card_url' => env(
                'SPGATEWAY_CREDIT_CARD_URL',
                'https://ccore.newebpay.com/API/CreditCard'
            ),
            // 取消授權
            'cancel_url' => env(
                'SPGATEWAY_CANCEL_URL',
                'https://ccore.newebpay.com/API/CreditCard/Cancel'
            ),
            // 退刷
            'close_url' => env(
                'SPGATEWAY_CLOSE_URL',
                'https://ccore.newebpay.com/API/CreditCard/Close'
            ),
            // 查詢
            'query_url' => env(
                'SPGATEWAY_QUERY_URL',
                'https://ccore.newebpay.com/API/QueryTradeInfo'
            ),
            // 抽成(正負向交易)
            'charge_instruct_url' => env(
                'SPGATEWAY_CHARGE_INSTRUCT_URL',
                'https://ccore.newebpay.com/API/ChargeInstruct'
            ),

            // 合作店家資訊
            'store' => [
                // PartnerID_
                'partner_id' => env('SPGATEWAY_STORE_PARTNER_ID', 'JOYM'),
                // HashKey
                'merchant_hash_key' => env('SPGATEWAY_MERCHANT_HASH_KEY'),
                // HashIV
                'merchant_iv_key' => env('SPGATEWAY_MERCHANT_IV_KEY'),

                // 建立店家的前綴
                'merchant_prefix' => env('SPGATEWAY_MERCHANT_PREFIX', 'JMP'),

                // 建立店家
                'create' => [
                    'url' => env(
                        'SPGATEWAY_CREATE_STORE_URL',
                        'https://ccore.newebpay.com/api/addmerchant'
                    ),
                    'version' => env('SPGATEWAY_CREATE_STORE_VERSION', 1.2),
                ],
                // 修改店家
                'update' => [
                    'url' => env(
                        'SPGATEWAY_UPDATE_STORE_URL',
                        'https://ccore.newebpay.com/api/addmerchant/modify'
                    ),
                    'version' => env('SPGATEWAY_UPDATE_STORE_VERSION', 1.0),
                ],
                // 交易手續費比例
                'agreed_fee' => env('SPGATEWAY_STORE_AGREED_FEE', 0.02),
                // 撥款天數
                'agreed_day' => env('SPGATEWAY_STORE_AGREED_DAY', 2),
            ],

            // 網頁轉跳的方式
            'mpg' => [
                'credit_card_url' => env(
                    'SPGATEWAY_MPG_GATEWAY_URL',
                    'https://ccore.newebpay.com/MPG/mpg_gateway'
                ),
                'merchant_id' => env('SPGATEWAY_MPG_MERCHANT_ID'),
                'hash_key' => env('SPGATEWAY_MPG_HASH_KEY'),
                'hash_iv' => env('SPGATEWAY_MPG_HASH_IV'),

                // 取消回到的連結
                'client_back_url' => env('SPGATEWAY_MPG_CLIENT_BACK_URL'),
                // 前端通知
                'return_url' => env('SPGATEWAY_MPG_RETURN_URL'),
                // 後端通知
                'notify_url' => env('SPGATEWAY_MPG_NOTIFY_URL'),
            ],
        ],
    ],
];
