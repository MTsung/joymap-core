<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class NotificationMemberWithdraw extends Model
{
    use HasFactory;

    protected $table = 'notification_member_withdraw';

    protected $guarded = ['id'];

    // 0 提領申請
    public const STATUS_WITHDRAW_APPLY = 0;
    // 1 匯款完成
    public const STATUS_REMITTANCE_COMPLETED = 1;
    // 2 預估分潤
    public const STATUS_ESTIMATED_PROFIT = 2;
    // 3 未加入方案通知
    public const STATUS_NOTIFY_REGISTER_NO_PLAN = 3;
    // 4 付費方案獎勵
    public const STATUS_PAID_PLAN_REWARD = 4;
    // 5 未加入回饋通知
    public const STATUS_NOTIFY_MEMBER_NO_REWARD = 5;

    public function getMorphClass(): string
    {
        return $this->getTable();
    }

    public function notify(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notify');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
