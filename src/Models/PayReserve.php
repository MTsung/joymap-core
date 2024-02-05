<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayReserve extends Model
{
    protected $table = 'pay_reserve';

    protected $guarded = ['id'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // 支付失敗
    public const STATUS_FAILED = -1;
    // 等待支付中
    public const STATUS_PENDING = 0;
    // 支付成功
    public const STATUS_SUCCESS = 1;
    // 支付過期
    public const STATUS_EXPIRED = 2;
    // 部分退款
    public const STATUS_PARTIAL_REFUND = 3;
    // 全額退款
    public const STATUS_FULL_REFUND = 4;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function joyPayStoreSetting(): BelongsTo
    {
        return $this->belongsTo(JoyPayStoreSetting::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class);
    }
}
