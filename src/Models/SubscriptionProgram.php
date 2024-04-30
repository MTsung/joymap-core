<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionProgram extends Model
{
    protected $table = 'subscription_programs';

    protected $guarded = ['id'];

    public const ANGEL_NAME = '天使';

    public const ARCHANGEL_NAME = '大天使';

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }
}
