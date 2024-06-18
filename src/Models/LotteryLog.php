<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotteryLog extends Model
{
    protected $table = 'lottery_logs';

    protected $guarded = ['id'];

    //每日登入獲得抽一次
    public const TYPE_EVERY_DAY_LOGIN = 0;
    //刷卡金額100以上
    public const TYPE_PAYMENT_100 = 1;
    //刷卡金額1000以上
    public const TYPE_PAYMENT_1000 = 2;
    //每累積5筆交易(包含退刷)
    public const TYPE_CUMULATIVE_5_TRANSACTIONS = 3;
    //當月累積金額滿3000元(包含退刷)每月一次
    public const TYPE_MONTH_CUMULATIVE_3000_AMOUNT = 4;
    //當月邀請註冊成功20位(每月一次)
    public const TYPE_MONTH_INVITE_20_REGISTER = 5;
    //當月累積邀請5位VIP加入(每月一次)
    public const TYPE_MONTH_CUMULATIVE_INVITE_5_VIP = 6;
    //每邀請10位VIP加入
    public const TYPE_INVITE_10_VIP = 7;

    // 未抽獎
    public const STATUS_NOT_DRAWN = 0;
    // 已抽獎
    public const STATUS_DRAWN = 1;
    // 已取消
    public const STATUS_CANCELLED = 2;

    public function lottery(): BelongsTo
    {
        return $this->belongsTo(Lottery::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }

    public function lotteryAward(): BelongsTo
    {
        return $this->belongsTo(LotteryAward::class);
    }

    public function coinLog(): HasOne
    {
        return $this->hasOne(CoinLog::class);
    }
}
