<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreTableCombination extends Model
{
    use HasFactory;

    protected $table = 'store_table_combinations';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'combination' => 'array',
        'people_combination' => 'array',
        'relation_ids' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeFloor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class);
    }
}
