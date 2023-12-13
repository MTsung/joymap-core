<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodType extends Model
{
    use HasFactory;

    protected $table = 'food_types';

    protected $guarded = ['id'];

    public function stores()
    {
        return $this->hasMany(StoreFoodType::class);
    }

    public function mainFoodType()
    {
        return $this->hasMany(MainFoodType::class, 'id', 'main_food_type_id');
    }
}
