<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionProgram extends Model
{
    protected $table = 'subscription_programs';

    protected $guarded = ['id'];

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }
}
