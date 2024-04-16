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

    protected $casts = [
        'subscription_start_at' => 'datetime',
        'subscription_end_at' => 'datetime',
    ];

    // 不再續訂
    const STATUS_DISABLED = 0;
    // 自動續訂
    const STATUS_ENABLE = 1;
    // 封存資料不撈取
    const STATUS_MOTHBALL = 99;

    protected static function booted()
    {
        static::addGlobalScope('skip_status_mothball', function ($builder) {
            $builder->where('member_dealers.status', '!=', self::STATUS_MOTHBALL);
        });
    }

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

    public function memberBank(): HasOne
    {
        return $this->hasOne(MemberBank::class, 'member_id', 'member_id');
    }

    public function subscriptionProgram(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgram::class);
    }

    public function nextSubscriptionProgram(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgram::class, 'next_subscription_program_id', 'id');
    }

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }

    public function subscriptionProgramPayLogs(): HasMany
    {
        return $this->hasMany(SubscriptionProgramPayLog::class);
    }

    public function fromInviteDealer(): BelongsTo
    {
        return $this->belongsTo(MemberDealer::class, 'from_invite_id', 'id');
    }

    public function inviteChildren(): HasMany
    {
        return $this->hasMany(MemberDealer::class, 'from_invite_id', 'id');
    }

    /**
     * invite_children_count
     * @return int
     */
    public function getInviteChildrenCountAttribute(): int
    {
        if(in_array($this->member_grade_type, [
            MemberGrade::TYPE_JOY_ARCHANGEL,
            MemberGrade::TYPE_JOY_SERAPH
        ])) {
            $fromInviteIds = [$this->id];
            $total = 0;
            do{
                $fromInviteIds = MemberDealer::whereIn('from_invite_id', $fromInviteIds)->get()->pluck('id')->toArray();
                $count = count($fromInviteIds);
                if($count > 0) {
                    $total += $count;
                }
            }while($count > 0);
            return (int)$total;
        } else {
            return (int)$this->inviteChildren()->count();
        }
    }

    /**
     * member_grade_type
     * @return int
     */
    public function getMemberGradeTypeAttribute(): int
    {
        return $this->member->memberGrade?->type ?? 0;
    }
}
