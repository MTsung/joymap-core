<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class NotificationOrder extends Model
{
    use HasFactory;

    protected $table = 'notification_order';

    protected $guarded = ['id'];

    // 預約成功 (使用者操作)
    const STATUS_USER_SUCCESS = 0;
    // 預約成功 (店家操作)
    const STATUS_STORE_SUCCESS = 1;
    // 預約修改 (使用者操作&店家操作)
    const STATUS_MODIFY = 2;
    // 取消預約 (使用者操作)
    const STATUS_USER_CANCEL = 3;
    // 取消預約 (店家操作)
    const STATUS_STORE_CANCEL = 4;
    // 預約提醒
    const STATUS_REMINDER = 5;
    // 預約提醒(無按鈕)
    const STATUS_REMINDER_NO_BUTTON = 6;
    // 已入座
    const STATUS_SEATED = 7;
    // 已入座(無按鈕)
    const STATUS_SEATED_NO_BUTTON = 8;

    public function getMorphClass(): string
    {
        return $this->getTable();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function notify(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notify');
    }
}
