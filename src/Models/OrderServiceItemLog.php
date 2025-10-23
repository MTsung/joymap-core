<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderServiceItemLog extends Model
{
    use HasFactory;

    protected $table = 'order_service_item_logs';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'original_data' => 'array',
        'updated_data' => 'array',
    ];

    public function orderServiceItem(): BelongsTo
    {
        return $this->belongsTo(OrderServiceItem::class);
    }
}
