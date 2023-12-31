<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDealerPointWithdraw extends Model
{
    use HasFactory;

    protected $table = 'member_dealer_point_withdraws';

    protected $guarded = ['id'];

    // 申請提領審核中
    const STATUS_UNDER_REVIEW = 0;
    // 提領已完成匯款
    const STATUS_COMPLETE_REMITTANCE = 1;

    public function memberDealerPointLog(): BelongsTo
    {
        return $this->belongsTo(MemberDealerPointLog::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

}
