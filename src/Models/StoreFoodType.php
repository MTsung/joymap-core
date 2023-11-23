<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreFoodType extends Model
{
    use HasFactory;

    protected $table = 'store_food_types';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function foodType()
    {
        return $this->belongsTo(FoodType::class);
    }
}
