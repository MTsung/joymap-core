<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class MainFoodType extends Model
{
    protected $table = 'main_food_types';

    protected $guarded = ['id'];

    public function foodTypes(): HasMany
    {
        return $this->hasMany(FoodType::class, 'main_food_type_id', 'id');
    }
}
