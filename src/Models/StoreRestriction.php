<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreRestriction extends Model
{
    use HasFactory;

    protected $table = 'store_restrictions';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
