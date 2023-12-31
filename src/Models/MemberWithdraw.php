<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberWithdraw extends Model
{
    use HasFactory;

    protected $table = "member_withdraw";

    protected $guarded = ['id'];

    // 提領現金
    public const TYPE_MONEY = 0;
    // 轉享樂幣
    public const TYPE_JCOIN = 1;

    // 待處理
    public const STATUS_PENDING = 0;
    // 匯款完成
    public const STATUS_COMPLETED = 1;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function coinLog(): HasOne
    {
        return $this->hasOne(CoinLog::class);
    }
}
