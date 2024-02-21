<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
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

    /**
     * 判斷是否有修改權
     * @param ?Authenticatable $user
     * @return bool
     */
    public function isOwns(?Authenticatable $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        if ($user instanceof StoreUser) {
            return $this->store_id == $user->store_id;
        }

        return false;
    }
}
