<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionProgramOrder extends Model
{
    protected $table = 'subscription_program_orders';

    protected $guarded = [];

    public function subscriptionProgram(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgram::class);
    }

    public function subscriptionProgramPayLog(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgramPayLog::class);
    }

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
