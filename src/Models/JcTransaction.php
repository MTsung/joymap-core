<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JcTransaction extends Model
{
    use HasFactory;

    protected $table = "jc_transactions";

    protected $guarded = ['id'];

    // 儲值
    public const TYPE_GIVE = 0;
    // 消費
    public const TYPE_USE = 1;
    // 過期
    public const TYPE_EXPIRE = 2;

    // 失敗
    public const STATUS_FAILURE = 0;
    // 成功
    public const STATUS_SUCCESS = 1;
    // 手動補發
    public const STATUS_MANUAL_RESEND = 2;

    public function jcCoins(): HasMany
    {
        return $this->hasMany(JcCoin::class, 'transaction_id', 'id');
    }
}
