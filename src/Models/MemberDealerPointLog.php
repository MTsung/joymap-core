<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberDealerPointLog extends Model
{
    use HasFactory;

    protected $table = 'member_dealer_point_logs';

    protected $guarded = ['id'];

    // 加入天使會員(經銷商)
    const TYPE_JOIN_DEALER = 0;
    // 提領點數
    const TYPE_WITHDRAW = 1;

    // 網紅0圓加入經銷商專案
    const TYPE_KOL_JOIN_DEALER = 2;

    // 處理中
    const STATUS_PROCESSING = 0;
    // 成功
    const STATUS_SUCCESS = 1;
    // 失敗
    const STATUS_FAIL = 2;
    // 取消
    const STATUS_CANCEL = 3;
    // 不符合資格(取消訂閱)
    const STATUS_CANCEL_SUBSCRIPTION = 4;
    // 不符合資格(未有介紹人)
    const STATUS_NO_INVITE = 5;
    // 備存
    const STATUS_KEEP = 99;

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealerPointWithdraw(): HasOne
    {
        return $this->hasOne(MemberDealerPointWithdraw::class);
    }

    public function subscriptionProgramOrder(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgramOrder::class);
    }

    public function childMemberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class, 'child_dealer_id', 'id');
    }
}
