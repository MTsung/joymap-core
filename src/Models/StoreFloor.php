<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeTables(): HasMany
    {
        return $this->hasMany(StoreTable::class);
    }

    public function storeTableCombinations(): HasMany
    {
        return $this->hasMany(StoreTableCombination::class);
    }

    public function storeFloorMap(): HasOne
    {
        return $this->hasOne(StoreFloorMap::class);
    }
}
