<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponNumberTransactionLog extends Model
{
    use HasFactory;

    protected $table = 'coupon_number_transaction_logs';

    const UPDATED_AT = null;

    protected $guarded = ['id'];

    // 核銷
    const ACTION_REDEEM = 0;
    // 取消核銷
    const ACTION_UNDO_REDEMPTION = 1;
    // 過期
    const ACTION_EXPIRE = 2;
    // 失效作廢
    const ACTION_INVALIDATE = 3;

    public function couponNumber(): BelongsTo
    {
        return $this->belongsTo(CouponNumber::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }

}
