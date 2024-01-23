<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionProgramCreditcardLog extends Model
{
    protected $table = 'subscription_program_creditcard_logs';

    protected $guarded = [];

    public function subscriptionProgramPayLog(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgramPayLog::class);
    }

    public function memberCreditCard(): BelongsTo
    {
        return $this->belongsTo(MemberCreditCard::class, 'credit_id', 'id');
    }
}
