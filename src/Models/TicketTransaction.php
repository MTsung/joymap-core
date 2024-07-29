<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketTransaction extends Model
{
    protected $table = 'ticket_transactions';

    protected $guarded = ['id'];

    // 初始
    public const STATUS_INIT = 0;
    // 成功 (付款成功)
    public const STATUS_SUCCESS = 1;
    // 失敗 (付款失敗)
    public const STATUS_FAILURE = 2;
    // 取消 (取消訂單)
    public const STATUS_CANCEL = 3;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }

    public function ticketNumberLogs(): HasMany
    {
        return $this->hasMany(TicketNumberLog::class);
    }
}
