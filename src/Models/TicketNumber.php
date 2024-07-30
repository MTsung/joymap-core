<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketNumber extends Model
{
    protected $table = 'ticket_numbers';

    protected $guarded = ['id'];

    protected $casts = [
        'request_log' => 'array',
    ];

    // 初始
    public const STATUS_INIT = 0;
    // 可以使用
    public const STATUS_USABLE = 1;
    // 已核銷
    public const STATUS_REDEEMED = 2;
    // 已過期
    public const STATUS_EXPIRED = 3;
    // 已作廢
    public const STATUS_INVALID = 4;
    // 停售
    public const STATUS_STOP_SALE = 5;

    //未標記使用
    public const IS_MARK_USE_OFF = 0;
    //已標記使用
    public const IS_MARK_USE_ON = 1;

    public function ticketBatch(): BelongsTo
    {
        return $this->belongsTo(TicketBatch::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function ticketNumberLogs(): HasMany
    {
        return $this->hasMany(TicketNumberLog::class);
    }
}
