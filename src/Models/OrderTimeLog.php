<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTimeLog extends Model
{
    use HasFactory;

    protected $table = 'order_time_logs';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
