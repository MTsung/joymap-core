<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTableCombineSetting extends Model
{
    use HasFactory;

    protected $table = 'store_table_combine_setting';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = ['id'];

    public function storeTable(): BelongsTo
    {
        return $this->belongsTo(StoreTable::class);
    }

    public function combineTable(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'combine_table_id');
    }

}
