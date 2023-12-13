<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreNotification extends Model
{
    use HasFactory;

    protected $table = 'store_notifications';

    public $timestamps = true;

    protected $guarded = ['id'];

    //未讀
    public const IS_READ_OFF = 0;
    //已讀
    public const IS_READ_ON = 1;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
