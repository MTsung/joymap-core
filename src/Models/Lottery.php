<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Lottery extends Model
{
    protected $table = 'lotteries';

    protected $guarded = ['id'];

    //一般轉盤
    public const TYPE_COMMON_TURNTABLE = 0;
    //黃金轉盤
    public const TYPE_SPECIAL_TURNTABLE = 1;
    //一般扭蛋
    public const TYPE_COMMON_GACHA = 2;
    //黃金扭蛋
    public const TYPE_SPECIAL_GACHA = 3;

    //未啟用
    public const IS_ENABLED_OFF = 0;
    //已啟用
    public const IS_ENABLED_ON = 1;

    public function lotteryAwards(): HasMany
    {
        return $this->hasMany(LotteryAward::class);
    }

    public function lotteryLogs(): HasMany
    {
        return $this->hasMany(LotteryLog::class);
    }
}
