<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreService extends Model
{
    use HasFactory;

    protected $table = 'store_services';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function settings()
    {
        return $this->hasMany(StoreServiceSetting::class);
    }
}
