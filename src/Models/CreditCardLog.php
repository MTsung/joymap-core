<?php

namespace Mtsung\JoymapCore\Models;


class CreditCardLog extends Model
{
    protected $table = 'creditcard_logs';

    protected $guarded = ['id'];

    public function payLog()
    {
        return $this->belongsToMany(PayLog::class, 'pay_credit_logs', 'creditcard_log_id', 'pay_log_id');
    }
}
