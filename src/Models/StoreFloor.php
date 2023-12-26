<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorDeletingEvent;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorUpdatedEvent;

class StoreFloor extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'updated' => StoreFloorUpdatedEvent::class,
        'deleting' => StoreFloorDeletingEvent::class,
    ];

    protected $table = 'store_floors';

    public $timestamps = true;

    protected $guarded = ['id'];

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
