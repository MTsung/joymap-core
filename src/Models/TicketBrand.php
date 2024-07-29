<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketBrand extends Model
{
    protected $table = 'ticket_brands';

    protected $guarded = ['id'];

    // 安源
    public const SOURCE_QWARE = 0;
    // 墨攻
    public const SOURCE_MOHIST = 1;

    //未啟用
    public const IS_ENABLED_OFF = 0;
    //已啟用
    public const IS_ENABLED_ON = 1;

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
