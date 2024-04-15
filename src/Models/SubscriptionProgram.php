<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionProgram extends Model
{
    protected $table = 'subscription_programs';

    protected $guarded = ['id'];

    public const ARCHANGEL_NAME = '大天使';

    public const SERAPH_NAME = '熾天使';

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }
}
