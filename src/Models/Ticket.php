<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $guarded = ['id'];

    // 商品兌換
    public const TYPE_EXCHANGE = 0;
    // 餘額劵
    public const TYPE_BALANCE = 1;

    //未啟用
    public const IS_ENABLED_OFF = 0;
    //已啟用
    public const IS_ENABLED_ON = 1;

    public function ticketBrand(): BelongsTo
    {
        return $this->belongsTo(TicketBrand::class);
    }

    public function ticketBatchs(): HasMany
    {
        return $this->hasMany(TicketBatch::class);
    }

    public function ticketNumbers(): HasMany
    {
        return $this->hasMany(TicketNumber::class);
    }

    public function ticketTransactions(): HasMany
    {
        return $this->hasMany(TicketTransaction::class);
    }
}
