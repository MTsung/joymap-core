<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouponNumber extends Model
{
    use HasFactory;

    protected $table = 'coupon_numbers';

    protected $guarded = ['id'];

    // 可以使用
    const STATUS_AVAILABLE = 0;
    // 已核銷
    const STATUS_REDEEMED = 1;
    // 過期
    const STATUS_EXPIRED = 2;
    // 失效作廢
    const STATUS_INVALIDATED = 3;

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function couponNumberTransactionLogs(): HasMany
    {
        return $this->hasMany(CouponNumberTransactionLog::class);
    }

}
