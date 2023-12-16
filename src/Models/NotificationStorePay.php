<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationStorePay extends Model
{
    use HasFactory;

    protected $table = 'notification_store_pay';

    protected $guarded = ['id'];

    // 不顯示評論按鈕
    const STATUS_HIDE_BUTTON = 0;
    // 需顯示評論按鈕
    const STATUS_SHOW_BUTTON = 1;

    public function getMorphClass()
    {
        return $this->getTable();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function payLog()
    {
        return $this->belongsTo(PayLog::class, 'pay_log_id');
    }

    public function notify()
    {
        return $this->morphOne(Notification::class, 'notify');
    }
}
