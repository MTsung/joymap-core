<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTagSetting extends Model
{
    use HasFactory;

    protected $table = 'order_tag_settings';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function storeTag()
    {
        return $this->belongsTo(StoreTag::class);
    }
}
