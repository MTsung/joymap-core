<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JcCoin extends Model
{
    use HasFactory;

    protected $table = "jc_coins";

    protected $guarded = ['id'];

    // 儲值
    public const TYPE_GIVE = 0;
    // 消費
    public const TYPE_USE = 1;
    // 過期
    public const TYPE_EXPIRE = 2;

    // JOYMAP
    public const FROM_PARTNER_JOYMAP = 1;
    // TWDD
    public const FROM_PARTNER_TWDD = 2;

    public function jcCoinLogs(): HasMany
    {
        return $this->hasMany(JcCoin::class, 'transaction_id', 'id');
    }

    public function coinLogs(): HasOne
    {
        return $this->hasOne(CoinLog::class, 'coin_id', 'transaction_id');
    }
}
