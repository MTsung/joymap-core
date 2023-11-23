<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $guarded = [];

    protected $appends = [
        'type'
    ];

    // 下架
    public const STATUS_OFF_SHELF = 0;
    // 上架
    public const STATUS_ON_SHELF = 1;

    /**
     * type
     * 通知類型
     *
     * @return string|null
     */
    public function getTypeAttribute()
    {
        switch ($this->notify_type) {
            case 'notification_platform':
                return 'platform';
            case 'notification_order':
                return 'order';
            case 'notification_store_pay':
                return 'store_pay';
            case 'notification_member_withdraw':
                return 'member_withdraw';
            case 'notification_new_register':
                return 'new_register';
            default:
                return null;
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                                  RELATIONS                                 */
    /* -------------------------------------------------------------------------- */
    public function notify()
    {
        return $this->morphTo();
    }

    public function notificationMemberRead()
    {
        return $this->hasOne(NotificationMemberRead::class);
    }
}
