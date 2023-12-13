<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreBusinessTime extends Model
{
    use HasFactory;

    protected $table = 'store_business_time';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
