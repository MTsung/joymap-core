<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionProgramPayLog extends Model
{
    protected $table = 'subscription_program_pay_logs';

    protected $guarded = [];

    // 刷卡失敗
    public const STATUS_FAIL = 0;
    // 刷卡成功
    public const STATUS_SUCCESS = 1;
    // 無刷卡動作
    public const STATUS_NO_ACTION = 2;
    // 退刷
    public const STATUS_REFUND = 3;

    public function creditCardLogs(): HasMany
    {
        return $this->hasMany(SubscriptionProgramCreditcardLog::class);
    }

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
