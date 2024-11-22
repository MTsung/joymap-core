<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderSetting extends Model
{
    use HasFactory;

    protected $table = 'order_settings';

    public $timestamps = false;

    protected $guarded = ['id'];

    public const ORDER_UNIT_MINUTE = ['10', '15', '20', '30', '60', '90'];
    public const CAN_ORDER_DAY = ['7', '14', '30', '60', '90', '120', '150', '180'];
    public const SAME_TIME_ORDER_TOTAL = ['2', '4', '6', '8', '10', '16', '20', '26', '30'];
    public const SINGLE_TIME_ORDER_TOTAL = ['2', '4', '6', '8', '10', '12'];
    public const FINAL_ORDER_MINUTE = ['0', '30', '60', '120', '180', '240', '300', '360', '720', '1440'];
    public const FINAL_CANCEL_MINUTE = ['0', '30', '60', '90', '120', '150', '180'];
    public const HOLD_ORDER_MINUTE = ['5', '10', '15', '20', '25', '30'];

    public const ORDER_UNIT_MINUTE_DEFAULT = '30';// 預設的預約訂位區間 (分鐘)
    public const CAN_ORDER_DAY_DEFAULT = '14';// 預設於多久前可開放預約(天)
    public const SINGLE_TIME_ORDER_TOTAL_DEFAULT = '6';// 預設的單次訂位最大總數(含小孩)
    public const FINAL_ORDER_MINUTE_DEFAULT = '0';// 預設須於多久前完成預約(分鐘)
    public const FINAL_CANCEL_MINUTE_DEFAULT = '0';// 預設最晚多久以前可以取消訂位(分鐘)
    public const HOLD_ORDER_MINUTE_DEFAULT = '10';// 預設訂位保留時間(分鐘)

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
