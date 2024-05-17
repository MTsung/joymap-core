<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubscriptionProgramOrder extends Model
{
    protected $table = 'subscription_program_orders';

    protected $guarded = [];

    protected $casts = [
        'period_start_at' => 'datetime',
        'period_end_at' => 'datetime',
    ];

    // 處理中
    public const STATUS_PROCESSING = 0;
    // 成功
    public const STATUS_SUCCESS = 1;
    // 失敗
    public const STATUS_FAILURE = 2;

    public function subscriptionProgram(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgram::class);
    }

    public function subscriptionProgramPayLog(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgramPayLog::class);
    }

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberBonus(): HasMany
    {
        return $this->hasMany(MemberBonus::class);
    }


    public function memberBonusLogs(): HasMany
    {
        return $this->hasMany(MemberBonusLogs::class);
    }

    public function memberDealerBonuses(): HasMany
    {
        return $this->hasMany(MemberDealerBonus::class);
    }

    public function memberDealerPointLog(): HasOne
    {
        return $this->hasOne(MemberDealerPointLog::class);
    }
}
