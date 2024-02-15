<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDealerBonusWithdraw extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // 申請提領審核中
    const STATUS_UNDER_REVIEW = 0;
    // 提領已完成匯款
    const STATUS_COMPLETE_REMITTANCE = 1;

    public function memberDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
