<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreRecommender extends Model
{
    protected $table = 'store_recommender';

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function storeSubscription(): HasMany
    {
        return $this->hasMany(StoreSubscription::class);
    }
}
