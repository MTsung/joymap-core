<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JcCoin extends Model
{
    use HasFactory;

    protected $table = "jc_coins";

    protected $guarded = [];

    // 儲值
    public const TYPE_GIVE = 0;
    // 消費
    public const TYPE_USE = 1;
    // 過期
    public const TYPE_EXPIRE = 2;

    // JOYMAP
    public const FROM_SOURCE_JOYMAP = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;

    // JOYMAP
    public const FROM_PARTNER_JOYMAP = 1;
    // TWDD
    public const FROM_PARTNER_TWDD = 2;

    public function jcCoinLogs()
    {
        return $this->hasMany(JcCoin::class, 'transaction_id', 'id');
    }

    public function coinLogs()
    {
        return $this->hasOne(CoinLog::class, 'coin_id', 'transaction_id');
    }
}