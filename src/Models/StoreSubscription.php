<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreSubscription extends Model
{
    protected $table = 'store_subscription';

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeRecommender(): BelongsTo
    {
        return $this->belongsTo(StoreRecommender::class);
    }

    public function storePlan(): BelongsTo
    {
        return $this->belongsTo(StorePlan::class);
    }

    public function storePayLogs(): HasMany
    {
        return $this->hasMany(StorePayLogs::class);
    }

    public function storeSubscriptionPeriod(): HasMany
    {
        return $this->hasMany(StoreSubscriptionPeriod::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function storeSubscriptionAddPlan(): HasMany
    {
        return $this->hasMany(StoreSubscriptionAddPlan::class);
    }
}
