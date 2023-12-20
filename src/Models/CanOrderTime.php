<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CanOrderTime extends Model
{
    use HasFactory;

    protected $table = 'can_order_time';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'begin_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
