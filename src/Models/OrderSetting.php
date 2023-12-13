<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderSetting extends Model
{
    use HasFactory;

    protected $table = 'order_settings';

    public $timestamps = false;

    protected $guarded = ['id'];

    public const ORDER_UNIT_MINUTE = ['10', '15', '20', '30', '60'];
    public const CAN_ORDER_DAY = ['7', '14', '30', '60', '90', '120', '150', '180'];
    public const SAME_TIME_ORDER_TOTAL = ['2', '4', '6', '8', '10', '16', '20', '26', '30'];
    public const SINGLE_TIME_ORDER_TOTAL = ['2', '4', '6', '8', '10'];
    public const FINAL_ORDER_MINUTE = ['0', '30', '60', '120', '180', '240', '300', '360', '720', '1440'];
    public const FINAL_CANCEL_MINUTE = ['0', '30', '60'];
    public const HOLD_ORDER_MINUTE = ['10', '15', '20', '25', '30'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
