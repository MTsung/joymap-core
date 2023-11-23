<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHourSetting extends Model
{
    use HasFactory;

    protected $table = 'order_hour_settings';

    public $timestamps = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
