<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableCreatedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletingEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableUpdatedEvent;

class StoreTable extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => StoreTableCreatedEvent::class,
        'updated' => StoreTableUpdatedEvent::class,
        'deleting' => StoreTableDeletingEvent::class,
        'deleted' => StoreTableDeletedEvent::class,
    ];

    protected $table = 'store_tables';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function storeFloor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class);
    }

    // 找該桌位「可併桌的桌位」
    public function combineTables(): BelongsToMany
    {
        return $this->belongsToMany(StoreTable::class, 'store_table_combine_setting', 'store_table_id', 'combine_table_id');
    }

    // 找「該桌位被併桌」的桌位
    public function combinedByTables(): BelongsToMany
    {
        return $this->belongsToMany(StoreTable::class, 'store_table_combine_setting', 'combine_table_id', 'store_table_id');
    }

    public function combineSettings(): HasMany
    {
        return $this->hasMany(StoreTableCombineSetting::class, 'store_table_id');
    }
}
