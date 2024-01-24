<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionProgramPayLog extends Model
{
    protected $table = 'subscription_program_pay_logs';

    protected $guarded = [];

    // 刷卡失敗
    public const USER_PAY_STATUS_FAIL = 0;
    // 刷卡成功
    public const USER_PAY_STATUS_SUCCESS = 1;
    // 無刷卡動作
    public const USER_PAY_STATUS_NO_ACTION = 2;
    // 退刷
    public const USER_PAY_STATUS_REFUND = 3;

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
