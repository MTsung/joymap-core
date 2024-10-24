<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSubscriptionPeriod extends Model
{
    protected $table = 'store_subscription_period';

    protected $guarded = ['id'];

    // 系統訂閱
    public const TYPE_SYSTEM = 0;
    // 加購項目
    public const TYPE_ADD = 1;

    public function storeSubscription(): BelongsTo
    {
        return $this->belongsTo(StoreSubscription::class);
    }

    public function storePayLogs(): BelongsTo
    {
        return $this->belongsTo(StorePayLogs::class, 'store_pay_log_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storePlan(): BelongsTo
    {
        return $this->belongsTo(StorePlan::class);
    }

    public function storePlanSetting(): BelongsTo
    {
        return $this->belongsTo(StorePlanSetting::class, 'store_plan_id', 'store_plan_id');
    }
}
