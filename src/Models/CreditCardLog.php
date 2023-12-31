<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CreditCardLog extends Model
{
    protected $table = 'creditcard_logs';

    protected $guarded = ['id'];

    public function payLog(): BelongsToMany
    {
        return $this->belongsToMany(PayLog::class, 'pay_credit_logs', 'creditcard_log_id', 'pay_log_id');
    }
}
