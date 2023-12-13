<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTableCombineSetting extends Model
{
    use HasFactory;

    protected $table = 'store_table_combine_setting';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = ['id'];

    public function storeTable()
    {
        return $this->belongsTo(StoreTable::class);
    }

    public function combineTable()
    {
        return $this->belongsTo(Store::class, 'combine_table_id');
    }

}
