<?php

namespace Mtsung\JoymapCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public $timestamps = true;

    protected $guarded = ['id'];

    // 預約
    public const TYPE_RESERVE = 0;
    // 現場候位
    public const TYPE_ONSITE_WAIT = 1;
    // 現場入座
    public const TYPE_ONSITE_SEAT = 2;

    // 取消訂位
    public const STATUS_CANCEL_BY_USER = 0;
    // 成功訂位
    public const STATUS_SUCCESS_BOOKING_BY_USER = 1;
    // 餐廳代客訂位
    public const STATUS_SUCCESS_BOOKING_BY_STORE = 2;
    // 餐廳取消訂位
    public const STATUS_CANCEL_BY_STORE = 3;
    // 保留座位
    public const STATUS_RESERVED_SEAT = 4;
    // 已入座
    public const STATUS_SEATED = 5;
    // 已結帳 (已離座)
    public const STATUS_LEFT_SEAT = 6;
    // 未出席 no-show
    public const STATUS_NO_SHOW = 7;

    // JoyBooking
    public const FROM_SOURCE_JOY_BOOKING = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;
    // Joymap
    public const FROM_SOURCE_JOYMAP = 2;
    // 餐廳代客訂位
    public const FROM_SOURCE_RESTAURANT_BOOKING = 3;
    // Joymap_APP
    public const FROM_SOURCE_JOYMAP_APP = 4;
    // TW 授權
    public const FROM_SOURCE_TW_AUTHORIZATION = 5;
    // Google訂位
    public const FROM_SOURCE_GOOGLE_BOOKING = 6;

    // 訂位成功
    public const SUCCESS_BOOKING = [
        self::STATUS_SUCCESS_BOOKING_BY_USER,
        self::STATUS_SUCCESS_BOOKING_BY_STORE,
    ];
    // 待入座
    public const TO_BE_SEATED = [
        self::STATUS_SUCCESS_BOOKING_BY_USER,
        self::STATUS_SUCCESS_BOOKING_BY_STORE,
        self::STATUS_RESERVED_SEAT,
        self::STATUS_NO_SHOW,
    ];
    // 取消
    public const CANCEL = [
        self::STATUS_CANCEL_BY_USER,
        self::STATUS_CANCEL_BY_STORE,
    ];
    // 來店判斷
    public const VISIT = [
        self::STATUS_SEATED,
        self::STATUS_LEFT_SEAT,
    ];
    // 取消以外的狀態
    public const NON_CANCEL_STATE = [
        self::STATUS_SUCCESS_BOOKING_BY_USER,
        self::STATUS_SUCCESS_BOOKING_BY_STORE,
        self::STATUS_RESERVED_SEAT,
        self::STATUS_SEATED,
        self::STATUS_LEFT_SEAT,
        self::STATUS_NO_SHOW,
    ];
    // 桌位已使用判斷
    public const TABLE_USING = [
        self::STATUS_SUCCESS_BOOKING_BY_USER,
        self::STATUS_SUCCESS_BOOKING_BY_STORE,
        self::STATUS_RESERVED_SEAT,
        self::STATUS_SEATED,
        self::STATUS_NO_SHOW,
    ];

    // 組合預約日期時間 RAW SQL
    public const RAW_RESERVATION_DATETIME = 'CONCAT(orders.reservation_date, " ", orders.reservation_time)';

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function tagSettings()
    {
        return $this->hasMany(OrderTagSetting::class);
    }

    public function memberComment()
    {
        return $this->hasOne(Comment::class);
    }

    public function timeLogs()
    {
        return $this->hasOne(OrderTimeLog::class);
    }

    public function notificationOrder()
    {
        return $this->hasMany(NotificationOrder::class);
    }

    public function orderTags()
    {
        return $this->belongsToMany(StoreTag::class, 'order_tag_settings', 'order_id', 'store_tag_id');
    }

    public function storeNotification()
    {
        return $this->hasMany(StoreNotification::class);
    }

    /**
     * 會是抓全部店家的會員標籤，要再 where store_id
     * @return mixed
     */
    public function memberTags()
    {
        return $this->belongsToMany(StoreTag::class, 'member_tag_settings', 'member_id', 'store_tag_id', 'member_id');
    }

    /**
     * addReservationDatetime
     * add reservation_datetime scope
     */
    public function scopeAddReservationDatetime($query)
    {
        return $query->addSelect(
            DB::raw(self::RAW_RESERVATION_DATETIME . ' AS reservation_datetime'),
        );
    }

    /**
     * addPeopleNum
     * add people_num scope
     */
    public function scopeAddPeopleNum($query)
    {
        return $query->addSelect(
            DB::raw("(orders.adult_num + orders.child_num) AS people_num"),
        );
    }

    /**
     * info_url
     * @return string
     */
    public function getInfoUrlAttribute(): string
    {
        return config('joymap.domain.www') . '/booking-result/' . $this->id;
    }
}
