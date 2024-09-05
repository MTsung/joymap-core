<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCreditcardLogs extends Model
{
    protected $table = 'store_creditcard_logs';

    protected $guarded = ['id'];

    public function storePayLogs(): BelongsTo
    {
        return $this->belongsTo(StorePayLogs::class, 'store_pay_log_id');
    }
}
