<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSubscriptionAddPlan extends Model
{
    protected $table = 'store_subscription_add_plan';

    protected $guarded = ['id'];

    public function storePlan(): BelongsTo
    {
        return $this->belongsTo(StorePlan::class);
    }

    public function storeSubscription(): BelongsTo
    {
        return $this->belongsTo(StoreSubscription::class);
    }
}
