<?php

namespace Mtsung\JoymapCore\Models;


class MainFoodType extends Model
{
    protected $table = 'main_food_types';

    protected $guarded = [];

    public function foodTypes()
    {
        return $this->hasMany(FoodType::class, 'main_food_type_id', 'id');
    }
}
