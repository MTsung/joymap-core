<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDealer extends Model
{
    use HasFactory;

    protected $table = 'member_dealers';

    protected $guarded = [];

    // 停用退出
    const STATUS_DISABLED = 0;
    // 正常啟用
    const STATUS_ENABLE = 1;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealerRelation()
    {
        return $this->hasOne(MemberDealerRelation::class);
    }

    public function memberDealerBankSetting()
    {
        return $this->hasOne(MemberDealerBankSetting::class);
    }

    public function memberDealerPointLogs()
    {
        return $this->hasMany(MemberDealerPointLog::class);
    }

    public function couponNumbers()
    {
        return $this->hasMany(CouponNumber::class);
    }

    public function memberDealerRecommendStores()
    {
        return $this->hasMany(MemberDealerRecommendStore::class);
    }

    public function memberDealerBonuses()
    {
        return $this->hasMany(MemberDealerBonus::class);
    }

    public function memberDealerBonusWithdraws()
    {
        return $this->hasMany(MemberDealerBonusWithdraw::class);
    }

    public function memberBanks()
    {
        return $this->hasMany(MemberBank::class, 'member_id', 'member_id');
    }
}
