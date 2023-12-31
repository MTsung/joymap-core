<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDealerBonusWithdraw extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
