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
    //一般刮刮樂
    public const TYPE_COMMON_SCRATCHCARD = 2;
    //黃金刮刮樂
    public const TYPE_SPECIAL_SCRATCHCARD = 3;
    //一般轉盤圖標網址
    public const ICON_URL_COMMON_TURNTABLE = 'https://storage.googleapis.com/joymap-store/lottery/wheel_icon.png';
    //黃金轉盤圖標網址
    public const ICON_URL_SPECIAL_TURNTABLE = 'https://storage.googleapis.com/joymap-store/lottery/gold_wheel_icon.png';
    //一般刮刮樂
    public const ICON_URL_COMMON_SCRATCHCARD = 'https://storage.googleapis.com/joymap-store/lottery/scratchcard_icon.png';
    //黃金刮刮樂
    public const ICON_URL_SPECIAL_SCRATCHCARD = 'https://storage.googleapis.com/joymap-store/lottery/gold_scratchcard_icon.png';

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
