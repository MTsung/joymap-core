<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StorePlan extends Model
{
    protected $table = 'store_plan';

    protected $guarded = ['id'];

    public function storeSubscription(): HasMany
    {
        return $this->hasMany(StoreSubscription::class);
    }

    public function storePlanSetting(): HasOne
    {
        return $this->hasOne(StorePlanSetting::class);
    }
}
