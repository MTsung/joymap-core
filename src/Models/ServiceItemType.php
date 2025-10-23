<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceItemType extends Model
{
    use HasFactory;

    protected $table = 'service_item_types';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
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
