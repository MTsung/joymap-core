<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorePayLogs extends Model
{
    protected $table = 'store_pay_logs';

    protected $guarded = ['id'];

    // 刷卡失敗
    public const STATUS_FAIL = 0;
    // 刷卡成功
    public const STATUS_SUCCESS = 1;
    // 無刷卡動作
    public const STATUS_NO_ACTION = 2;
    // 退刷
    public const STATUS_REFUND = 3;

    public function storeSubscription(): BelongsTo
    {
        return $this->belongsTo(StoreSubscription::class, 'store_subscription_id');
    }

    public function storeCreditcardLogs(): HasMany
    {
        return $this->hasMany(StoreCreditcardLogs::class, 'store_pay_log_id');
    }
}
