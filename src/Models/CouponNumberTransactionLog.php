<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponNumberTransactionLog extends Model
{
    use HasFactory;

    protected $table = 'coupon_number_transaction_logs';

    const UPDATED_AT = null;

    protected $guarded = [];

    // 核銷
    const ACTION_REDEEM = 0;
    // 取消核銷
    const ACTION_UNDO_REDEMPTION = 1;
    // 過期
    const ACTION_EXPIRE = 2;
    // 失效作廢
    const ACTION_INVALIDATE = 3;

    public function couponNumber()
    {
        return $this->belongsTo(CouponNumber::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function payLog()
    {
        return $this->belongsTo(PayLog::class);
    }

}
