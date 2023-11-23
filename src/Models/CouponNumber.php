<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponNumber extends Model
{
    use HasFactory;

    protected $table = 'coupon_numbers';

    protected $guarded = [];

    // 可以使用
    const STATUS_AVAILABLE = 0;
    // 已核銷
    const STATUS_REDEEMED = 1;
    // 過期
    const STATUS_EXPIRED = 2;
    // 失效作廢
    const STATUS_INVALIDATED = 3;

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function couponNumberTransactionLogs()
    {
        return $this->hasMany(CouponNumberTransactionLog::class);
    }

}
