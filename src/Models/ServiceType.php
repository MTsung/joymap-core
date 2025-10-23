<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'service_types';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function mainFoodType(): BelongsTo
    {
        return $this->belongsTo(MainFoodType::class);
    }

    public function serviceItemTypes(): HasMany
    {
        return $this->hasMany(ServiceItemType::class);
    }

    public function orderServiceItems(): HasMany
    {
        return $this->hasMany(OrderServiceItem::class);
    }

    public function orderServiceItemAddons(): HasMany
    {
        return $this->hasMany(OrderServiceItemAddon::class);
    }
}
