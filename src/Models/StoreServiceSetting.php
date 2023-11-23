<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreServiceSetting extends Model
{
    use HasFactory;

    protected $table = 'store_service_settings';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function service()
    {
        return $this->belongsTo(StoreService::class);
    }
}
