<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreMonthCount extends Model
{
    protected $table = 'store_month_count';

    protected $guarded = ['id'];

    // 處理中
    const STATUS_DEFAULT = 0;
    // 成功
    const STATUS_SUCCESS = 1;
    // 失敗
    const STATUS_FAIL = 2;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storePlan(): BelongsTo
    {
        return $this->belongsTo(StorePlan::class);
    }

    public function storePayLogs(): BelongsTo
    {
        return $this->belongsTo(StorePayLogs::class, 'store_pay_log_id');
    }

    public function storePlanSetting(): BelongsTo
    {
        return $this->belongsTo(StorePlanSetting::class, 'store_plan_id', 'store_plan_id');
    }
}
