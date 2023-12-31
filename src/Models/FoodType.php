<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodType extends Model
{
    use HasFactory;

    protected $table = 'food_types';

    protected $guarded = ['id'];

    public function stores(): HasMany
    {
        return $this->hasMany(StoreFoodType::class);
    }

    public function mainFoodType(): HasMany
    {
        return $this->hasMany(MainFoodType::class, 'id', 'main_food_type_id');
    }
}
