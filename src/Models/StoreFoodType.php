<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreFoodType extends Model
{
    use HasFactory;

    protected $table = 'store_food_types';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function foodType(): BelongsTo
    {
        return $this->belongsTo(FoodType::class);
    }
}
