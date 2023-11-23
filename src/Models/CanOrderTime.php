<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CanOrderTime extends Model
{
    use HasFactory;

    protected $table = 'can_order_time';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
