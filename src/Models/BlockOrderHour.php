<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockOrderHour extends Model
{
    use HasFactory;

    protected $table = 'block_order_hour';

    public $timestamps = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
