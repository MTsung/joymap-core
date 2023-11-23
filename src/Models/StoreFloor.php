<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreFloor extends Model
{
    use HasFactory;

    protected $table = 'store_floors';

    public $timestamps = true;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeTables()
    {
        return $this->hasMany(StoreTable::class);
    }

    public function storeTableCombinations()
    {
        return $this->hasMany(StoreTableCombination::class);
    }

    public function storeFloorMap()
    {
        return $this->hasOne(StoreFloorMap::class);
    }
}
