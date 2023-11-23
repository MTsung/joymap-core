<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDealerPointWithdraw extends Model
{
    use HasFactory;

    protected $table = 'member_dealer_point_withdraws';

    protected $guarded = [];

    // 申請提領審核中
    const STATUS_UNDER_REVIEW = 0;
    // 提領已完成匯款
    const STATUS_COMPLETE_REMITTANCE = 1;

    public function memberDealerPointLog()
    {
        return $this->belongsTo(MemberDealerPointLog::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
