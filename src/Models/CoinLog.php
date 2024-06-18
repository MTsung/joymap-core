<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinLog extends Model
{
    // 消費回饋
    public const TYPE_CONSUME_REWARD = 1;
    // 消費折抵
    public const TYPE_CONSUME_DISCOUNT = 2;
    // 系統回收
    public const TYPE_SYSTEM_RECLAIM = 3;
    // 系統回補
    public const TYPE_SYSTEM_COMPENSATION = 4;
    // 系統任務
    public const TYPE_SYSTEM_TASK = 5;
    // 活動加碼
    public const TYPE_ACTIVITY_BONUS = 6;
    // 天使紅利
    public const TYPE_JOY_FAN_REWARD = 7;
    // 提領天使紅利
    public const TYPE_WITHDRAW_FUN_REWARD = 8;
    // 活動任務
    public const TYPE_ACTIVITY_TASK = 9;
    // 抽獎活動
    public const TYPE_LOTTERY = 10;

    // JOYMAP
    public const FROM_SOURCE_JOYMAP = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;

    // 人為/系統 調整
    public const TYPE_CHANGES_BY_ADMIN = [
        self::TYPE_SYSTEM_RECLAIM,
        self::TYPE_SYSTEM_COMPENSATION,
    ];

    protected $table = 'coin_logs';

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function memberWithdraw(): BelongsTo
    {
        return $this->belongsTo(MemberWithdraw::class);
    }

    public function systemTask(): BelongsTo
    {
        return $this->belongsTo(SystemTask::class, 'system_task_id');
    }

    public function lotteryLog(): BelongsTo
    {
        return $this->belongsTo(LotteryLog::class);
    }
}
