<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreFloorMap extends Model
{
    use HasFactory;

    protected $table = 'store_floor_maps';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'map' => 'array',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class);
    }
}
