<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function storeFloor()
    {
        return $this->belongsTo(StoreFloor::class);
    }

    // 找該桌位「可併桌的桌位」
    public function combineTables()
    {
        return $this->belongsToMany(StoreTable::class, 'store_table_combine_setting', 'store_table_id', 'combine_table_id');
    }

    // 找「該桌位被併桌」的桌位
    public function combinedByTables()
    {
        return $this->belongsToMany(StoreTable::class, 'store_table_combine_setting', 'combine_table_id', 'store_table_id');
    }
}
