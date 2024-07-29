<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketNumberLog extends Model
{
    protected $table = 'ticket_number_logs';

    protected $guarded = ['id'];

    // 發放
    public const ACTION_GIVED = 0;
    // 取消發放
    public const ACTION_CANCEL_GIVED = 1;
    // 核銷
    public const ACTION_REDEEMED = 2;
    // 取消核銷
    public const ACTION_CANCEL_REDEEMED = 3;
    // 過期
    public const ACTION_EXPIRED = 4;
    // 失效作廢
    public const ACTION_INVALID = 5;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function ticketNumber(): BelongsTo
    {
        return $this->belongsTo(TicketNumber::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }

    public function ticketTransaction(): BelongsTo
    {
        return $this->belongsTo(TicketTransaction::class);
    }
}
