<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreFloorMap extends Model
{
    use HasFactory;

    protected $table = 'store_floor_maps';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'map' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(StoreFloor::class);
    }
}
