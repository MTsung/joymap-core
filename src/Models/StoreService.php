<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreService extends Model
{
    use HasFactory;

    protected $table = 'store_services';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function settings(): HasMany
    {
        return $this->hasMany(StoreServiceSetting::class);
    }
}
