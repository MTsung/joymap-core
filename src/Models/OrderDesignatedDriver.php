<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDesignatedDriver extends Model
{
    use HasFactory;

    protected $table = 'order_designated_driver';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
