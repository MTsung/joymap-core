<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorePayLogs extends Model
{
    protected $table = 'store_pay_logs';

    protected $guarded = ['id'];

    public function storeSubscription(): BelongsTo
    {
        return $this->belongsTo(StoreSubscription::class, 'store_subscription_id');
    }

    public function storeCreditcardLogs(): HasMany
    {
        return $this->hasMany(StoreCreditcardLogs::class, 'store_pay_log_id');
    }
}
