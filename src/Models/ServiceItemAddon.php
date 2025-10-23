<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceItemAddon extends Pivot
{
    protected $table = 'service_item_addons';

    public $timestamps = true;

    public $incrementing = false;

    protected $primaryKey = ['main_service_item_id', 'addon_service_item_id'];

    public function mainServiceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class, 'main_service_item_id');
    }

    public function addonServiceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class, 'addon_service_item_id');
    }
}
