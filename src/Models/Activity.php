<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $table = 'activities';

    protected $guarded = ['id'];

    // 已取消
    public const STATUS_CANCELLED = 0;
    // 執行中
    public const STATUS_IN_PROGRESS = 1;
    // 待執行
    public const STATUS_PENDING = 2;
    // 已結束
    public const STATUS_FINISHED = 3;

    // 指定
    public const STORE_TYPE_SPECIFIC = 0;
    // 全部
    public const STORE_TYPE_ALL = 1;

    // 支付金額%數
    public const EXTRA_TYPE_PERCENTAGE = 0;
    // 多少享樂幣
    public const EXTRA_TYPE_AMOUNT = 1;

    // 不限制
    public const EXTRA_RESTRICTION_TYPE_NONE = 0;
    // 單店
    public const EXTRA_RESTRICTION_TYPE_SINGLE_STORE = 1;
    // 總額
    public const EXTRA_RESTRICTION_TYPE_TOTAL_AMOUNT = 2;

    // 領取後多少天到期
    public const EXTRA_COIN_DEADLINE_AFTER_RECEIVE = 0;
    // 統一到期日
    public const EXTRA_COIN_DEADLINE_UNIFIED = 1;

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function coinLogs(): HasMany
    {
        return $this->hasMany(CoinLog::class);
    }
}
