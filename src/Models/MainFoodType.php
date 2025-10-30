<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class MainFoodType extends Model
{
    protected $table = 'main_food_types';

    protected $guarded = ['id'];

    // 餐廳美食
    public const ID_FOOD = 1;
    // 休閒娛樂
    public const ID_ENTERTAINMENT = 3;
    // 旅遊住宿
    public const ID_TRAVEL = 4;
    // 生活服務
    public const ID_SERVICE = 5;
    // 美容舒壓
    public const ID_BEAUTY = 6;

    public function foodTypes(): HasMany
    {
        return $this->hasMany(FoodType::class, 'main_food_type_id', 'id');
    }
}
