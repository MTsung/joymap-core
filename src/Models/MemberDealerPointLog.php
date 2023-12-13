<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    // 備存
    const STATUS_KEEP = 99;

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealerPointWithdraw()
    {
        return $this->hasOne(MemberDealerPointWithdraw::class);
    }

}
