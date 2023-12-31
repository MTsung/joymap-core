<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberDealer extends Model
{
    use HasFactory;

    protected $table = 'member_dealers';

    protected $guarded = ['id'];

    // 停用退出
    const STATUS_DISABLED = 0;
    // 正常啟用
    const STATUS_ENABLE = 1;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberDealerRelation(): HasOne
    {
        return $this->hasOne(MemberDealerRelation::class);
    }

    public function memberDealerBankSetting(): HasOne
    {
        return $this->hasOne(MemberDealerBankSetting::class);
    }

    public function memberDealerPointLogs(): HasMany
    {
        return $this->hasMany(MemberDealerPointLog::class);
    }

    public function couponNumbers(): HasMany
    {
        return $this->hasMany(CouponNumber::class);
    }

    public function memberDealerRecommendStores(): HasMany
    {
        return $this->hasMany(MemberDealerRecommendStore::class);
    }

    public function memberDealerBonuses(): HasMany
    {
        return $this->hasMany(MemberDealerBonus::class);
    }

    public function memberDealerBonusWithdraws(): HasMany
    {
        return $this->hasMany(MemberDealerBonusWithdraw::class);
    }

    public function memberBanks(): HasMany
    {
        return $this->hasMany(MemberBank::class, 'member_id', 'member_id');
    }
}
