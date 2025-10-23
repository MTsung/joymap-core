<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceItem extends Model
{
    use HasFactory;

    protected $table = 'service_items';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'is_addon' => 'boolean',
    ];

    // 收費方式
    public const CHARGE_METHOD_BY_TYPE = 1; // 分服務類型
    public const CHARGE_METHOD_UNIFIED = 2; // 不分服務類型

    // 狀態
    public const STATUS_DISABLED = 0; // 停用
    public const STATUS_ENABLED = 1;  // 啟用

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function serviceItemTypes(): HasMany
    {
        return $this->hasMany(ServiceItemType::class);
    }

    public function mainServiceItems(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceItem::class,
            'service_item_addons',
            'addon_service_item_id',
            'main_service_item_id'
        );
    }

    public function addonServiceItems(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceItem::class,
            'service_item_addons',
            'main_service_item_id',
            'addon_service_item_id'
        );
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
