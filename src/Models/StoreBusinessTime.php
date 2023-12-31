<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreBusinessTime extends Model
{
    use HasFactory;

    protected $table = 'store_business_time';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
