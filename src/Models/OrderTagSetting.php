<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTagSetting extends Model
{
    use HasFactory;

    protected $table = 'order_tag_settings';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function storeTag(): BelongsTo
    {
        return $this->belongsTo(StoreTag::class);
    }
}
