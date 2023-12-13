<?php

namespace Mtsung\JoymapCore\Models;


class PayLog extends Model
{
    protected $table = 'pay_logs';

    protected $guarded = ['id'];

    // 折抵類型 預設
    public const DISCOUNT_TYPE_DEFAULT = 0;
    // 折抵類型 享樂幣折抵
    public const DISCOUNT_TYPE_JCOIN = 1;
    // 折抵類型 享樂經銷券
    public const DISCOUNT_TYPE_COUPON = 2;

    // JOYMAP
    public const FROM_SOURCE_JOYMAP = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;

    /**
     * 要抓公司卡才會需要用這個
     */
    public function creditCardLogs()
    {
        return $this->belongsToMany(CreditCardLog::class, 'pay_credit_logs', 'pay_log_id', 'creditcard_log_id');
    }

    /**
     * 有 pay_log_id 的代表是刷 member 卡
     */
    public function memberCreditCardLog()
    {
        return $this->hasMany(CreditCardLog::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function coinLog()
    {
        return $this->hasMany(CoinLog::class, 'pay_log_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'pay_log_id', 'id');
    }
}
