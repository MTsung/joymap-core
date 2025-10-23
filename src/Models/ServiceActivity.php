<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceActivity extends Model
{
    use HasFactory;

    protected $table = 'service_activities';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'first_time_amount' => 'decimal:2',
        'regular_amount' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function orderServiceItems(): HasMany
    {
        return $this->hasMany(OrderServiceItem::class);
    }
}
