<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderServiceItemAddon extends Model
{
    use HasFactory;

    protected $table = 'order_service_item_addons';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'service_item_type_data' => 'array',
    ];

    public function orderServiceItem(): BelongsTo
    {
        return $this->belongsTo(OrderServiceItem::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function serviceItemType(): BelongsTo
    {
        return $this->belongsTo(ServiceItemType::class);
    }
}
