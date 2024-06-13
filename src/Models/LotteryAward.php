<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryAward extends Model
{
    protected $table = 'lottery_awards';

    protected $guarded = ['id'];

    //享樂幣
    public const TYPE_COIN = 0;

    //未啟用
    public const IS_ENABLED_OFF = 0;
    //已啟用
    public const IS_ENABLED_ON = 1;

    public function lottery(): BelongsTo
    {
        return $this->belongsTo(Lottery::class);
    }

    public function lotteryLogs(): HasMany
    {
        return $this->hasMany(LotteryLog::class);
    }
}
