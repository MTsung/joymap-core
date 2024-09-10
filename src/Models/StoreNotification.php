<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreNotification extends Model
{
    use HasFactory;

    protected $table = 'store_notifications';

    public $timestamps = true;

    protected $guarded = ['id'];

    // 未讀
    public const IS_READ_OFF = 0;
    // 已讀
    public const IS_READ_ON = 1;

    // 用戶取消
    public const STATUS_CANCEL_BY_USER = 0;
    // TWDD 訂位
    public const STATUS_ORDER_FROM_TWDD = 1;
    // Joymap(APP or Www) 訂位
    public const STATUS_ORDER_FROM_JOYMAP = 2;
    // Joymap(Web) 訂位
    public const STATUS_ORDER_FROM_JOY_BOOKING = 3;
    // 店家代客訂位
    public const STATUS_ORDER_FROM_STORE = 4;
    // 店家取消
    public const STATUS_CANCEL_BY_STORE = 5;
    // 評論
    public const STATUS_COMMENT = 6;
    // 支付
    public const STATUS_PAY = 7;
    // Google 訂位
    public const STATUS_ORDER_FROM_GOOGLE = 8;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
