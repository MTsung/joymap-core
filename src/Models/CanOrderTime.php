<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'people_array' => 'array',
        'table_array' => 'array',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
