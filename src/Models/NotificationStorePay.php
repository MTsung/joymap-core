<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class NotificationStorePay extends Model
{
    use HasFactory;

    protected $table = 'notification_store_pay';

    protected $guarded = ['id'];

    // 不顯示評論按鈕
    const STATUS_HIDE_BUTTON = 0;
    // 需顯示評論按鈕
    const STATUS_SHOW_BUTTON = 1;

    public function getMorphClass(): string
    {
        return $this->getTable();
    }

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
        return $this->belongsTo(PayLog::class, 'pay_log_id');
    }

    public function notify(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notify');
    }
}
