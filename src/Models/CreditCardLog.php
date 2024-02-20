<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CreditCardLog extends Model
{
    protected $table = 'creditcard_logs';

    protected $guarded = ['id'];

    public const COMPANY_CARD_HOLDER_NAME = '群悅科技';

    public function payLog(): BelongsToMany
    {
        return $this->belongsToMany(PayLog::class, 'pay_credit_logs', 'creditcard_log_id', 'pay_log_id');
    }
}
