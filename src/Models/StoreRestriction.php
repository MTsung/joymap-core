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

    // 預設的用餐限制ID (目前預設用餐限制兩小時)
    public const STORE_RESTRICTION_ID_DEFAULT = 4;

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
