<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialStoreBusinessTime extends Model
{
    use HasFactory;

    protected $table = 'special_store_business_time';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
