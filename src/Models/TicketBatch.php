<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketBatch extends Model
{
    protected $table = 'ticket_batchs';

    protected $guarded = ['id'];

    // 上傳中
    public const STATUS_UPLOADING = 0;
    // 成功
    public const STATUS_SUCCESS = 1;
    // 失敗
    public const STATUS_FAILURE = 2;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function ticketNumbers(): HasMany
    {
        return $this->hasMany(TicketNumber::class);
    }
}
