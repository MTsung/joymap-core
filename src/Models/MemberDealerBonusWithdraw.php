<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDealerBonusWithdraw extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
