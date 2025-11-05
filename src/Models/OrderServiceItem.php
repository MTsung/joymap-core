<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderServiceItem extends Model
{
    use HasFactory;

    protected $table = 'order_service_items';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'service_item_data' => 'array',
        'service_activity_data' => 'array',
    ];

    // 配送方式
    public const DELIVERY_TYPE_PICKUP = 1;   // 自取
    public const DELIVERY_TYPE_DELIVERY = 2; // 外送

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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

    public function serviceActivity(): BelongsTo
    {
        return $this->belongsTo(ServiceActivity::class);
    }

    public function orderServiceItemAddons(): HasMany
    {
        return $this->hasMany(OrderServiceItemAddon::class);
    }

    public function orderServiceItemLogs(): HasMany
    {
        return $this->hasMany(OrderServiceItemLog::class);
    }

    public function orderDesignatedDriver(): HasOne
    {
        return $this->hasOne(OrderDesignatedDriver::class, 'order_id', 'order_id');
    }
}
