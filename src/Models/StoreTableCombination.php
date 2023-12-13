<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTableCombination extends Model
{
    use HasFactory;

    protected $table = 'store_table_combinations';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $casts = [
        'combination' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeFloor()
    {
        return $this->belongsTo(StoreFloor::class);
    }
}
