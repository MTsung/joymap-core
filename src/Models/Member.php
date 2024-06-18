<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;


/**
 * @property string joy_dealer_start_at
 * @property int children_count
 * @property string avatar_url
 * @property string identity_front_url
 * @property bool has_identity_front
 * @property string identity_back_url
 * @property bool has_identity_back
 * @property string identity_account_url
 * @property bool has_identity_account
 * @property bool has_identity
 * @property string full_phone
 * @property array login_type
 *
 * @method Builder active()
 */
class Member extends User implements JWTSubject
{
    use HasFactory, SerializeDateTrait;

    protected $table = 'members';

    protected $guarded = ['id'];

    protected string $guard_name = 'store';

    public const GUEST_PHONE = '8787123456';

    // 手機未驗證
    public const PHONE_IS_INACTIVE = 0;
    // 手機已驗證
    public const PHONE_IS_ACTIVE = 1;

    // 電子郵件未驗證
    public const EMAIL_IS_INACTIVE = 0;
    // 電子郵件已驗證
    public const EMAIL_IS_ACTIVE = 1;

    // 正常
    public const STATUS_NORMAL = 1;
    // 凍結
    public const STATUS_FREEZE = 2;
    // 停權
    public const STATUS_SUSPENDED = 0;

    // 未開通
    public const IS_JOY_FAN_NOT_ACTIVATED = 0;
    // 已開通
    public const IS_JOY_FAN_ACTIVATED = 1;

    // 一般會員 member_grade_id
    public const GRADE_NORMAL = 1;
    // 樂粉 member_grade_id
    public const GRADE_JOY_FAN = 2;
    // 經銷 member_grade_id
    public const GRADE_JOY_DEALER = 3;
    // 天使 member_grade_id
    public const GRADE_JOY_ANGEL = 4;
    // 大天使 member_grade_id
    public const GRADE_JOY_ARCHANGEL = 5;

    // 女
    public const GENDER_FEMALE = 0;
    // 男
    public const GENDER_MALE = 1;
    // 未知
    public const GENDER_UNKNOWN = 2;

    // JoyBooking
    public const FROM_SOURCE_JOY_BOOKING = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;
    // Joymap
    public const FROM_SOURCE_JOYMAP = 2;
    // 餐廳代客訂位
    public const FROM_SOURCE_RESTAURANT_BOOKING = 3;
    // Joymap_APP
    public const FROM_SOURCE_JOYMAP_APP = 4;
    // TW 授權
    public const FROM_SOURCE_TW_AUTHORIZATION = 5;
    // Google訂位
    public const FROM_SOURCE_GOOGLE_BOOKING = 6;

    // 本國人
    public const IS_FOREIGNER_LOCAL = 0;
    // 外國人
    public const IS_FOREIGNER_FOREIGN = 1;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            // 因為要相容舊的 JWT 所以給這個值，這個值底層是把 Auth Model Hash 起來判斷用的
            'prv' => '8665ae9775cf26f6b8e496f86fa536d68dd71818',
            // 密碼有改過的話要重新登入，給中介層檢查用
            'ppp' => md5($this->password),
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payLogs(): HasMany
    {
        return $this->hasMany(PayLog::class);
    }

    public function tagSettings(): HasMany
    {
        return $this->hasMany(MemberTagSetting::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function notificationOrder(): HasMany
    {
        return $this->hasMany(NotificationOrder::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function memberDeviceTokens(): HasMany
    {
        return $this->hasMany(MemberPush::class, 'member_id', 'id');
    }

    public function memberRelation(): HasOne
    {
        return $this->hasOne(MemberRelation::class);
    }

    public function relationChildren(): HasMany
    {
        return $this->hasMany(MemberRelation::class, 'parent_member_id', 'id');
    }

    public function relationGrandChildren(): HasMany
    {
        return $this->hasMany(MemberRelation::class, 'grand_parent_member_id', 'id');
    }

    public function memberBonuses(): HasMany
    {
        return $this->hasMany(MemberBonus::class, 'member_id', 'id');
    }

    public function memberBonusLogs(): HasMany
    {
        return $this->hasMany(MemberBonusLogs::class, 'member_id', 'id');
    }

    public function memberBank(): HasOne
    {
        return $this->hasOne(MemberBank::class, 'member_id', 'id');
    }

    public function deleteLogs(): HasMany
    {
        return $this->hasMany(MemberDeleteLog::class, 'member_id', 'id');
    }

    public function jcUser(): BelongsTo
    {
        return $this->belongsTo(JcUser::class);
    }

    public function jcCoins(): HasMany
    {
        return $this->hasMany(JcCoin::class, 'user_id', 'jc_user_id');
    }

    public function chargePlans(): BelongsToMany
    {
        return $this->belongsToMany(ChargePlan::class, 'member_charge_plan', 'member_id', 'charge_plan_id')->withPivot('status', 'receiver', 'receiver_phone', 'receiver_address');
    }

    public function memberChargePlans(): HasMany
    {
        return $this->hasMany(MemberChargePlan::class);
    }

    public function memberLoginLogs(): HasMany
    {
        return $this->hasMany(MemberLoginLog::class);
    }

    public function memberGrade(): BelongsTo
    {
        return $this->belongsTo(MemberGrade::class);
    }

    public function memberDealer(): hasOne
    {
        return $this->hasOne(MemberDealer::class)->ofMany(['id' => 'max'], function ($query) {
            $query->where('member_dealers.status', '!=', MemberDealer::STATUS_MOTHBALL);
        });
    }

    public function memberGradeChangeLogs(): HasMany
    {
        return $this->hasMany(MemberGradeChangeLog::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(StoreTag::class, 'member_tag_settings', 'member_id', 'store_tag_id');
    }

    public function notificationMemberRead(): HasMany
    {
        return $this->hasMany(NotificationMemberRead::class);
    }

    public function coinLogs(): HasMany
    {
        return $this->hasMany(CoinLog::class);
    }

    public function relationChilren(): HasMany
    {
        return $this->hasMany(MemberRelation::class, 'parent_member_id', 'id');
    }

    public function relationGrandChilren(): HasMany
    {
        return $this->hasMany(MemberRelation::class, 'grand_parent_member_id', 'id');
    }

    public function jcTransactionLogs(): HasMany
    {
        return $this->hasMany(JcTransaction::class, 'user_id', 'jc_user_id');
    }

    public function memberWithdraw(): HasMany
    {
        return $this->hasMany(MemberWithdraw::class, 'member_id', 'id');
    }

    public function memberInviteRelation(): HasOne
    {
        return $this->hasOne(MemberInviteRelation::class);
    }

    public function fromInvite(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'from_invite_id', 'id');
    }

    public function invite(): HasMany
    {
        return $this->hasMany(Member::class, 'from_invite_id', 'id');
    }

    public function systemTaskLogs(): HasMany
    {
        return $this->hasMany(SystemTaskLog::class, 'member_id');
    }

    public function subscriptionProgramOrders(): HasMany
    {
        return $this->hasMany(SubscriptionProgramOrder::class);
    }

    public function memberCreditCards(): HasMany
    {
        return $this->hasMany(MemberCreditCard::class);
    }

    public function memberWithdrawApplicationForm(): HasOne
    {
        return $this->hasOne(MemberWithdrawApplicationForm::class, 'member_id', 'id');
    }

    public function lotteryLogs(): HasMany
    {
        return $this->hasMany(LotteryLog::class);
    }

    // 抓取未停權的會員
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_NORMAL);
    }

    /**
     * storeMemberComments
     * join 店家的會員備註 scope
     */
    public function scopeStoreMemberComments($query)
    {
        if (!Auth::check()) return $query;
        if (!$storeId = Auth::user()?->store_id) return $query;

        return $query->leftJoin('store_member_comments', function ($join) use ($storeId) {
            $join->on('store_member_comments.member_id', 'members.id');
            $join->where('store_member_comments.store_id', $storeId);
        })->addSelect([
            'store_member_comments.comment as store_member_comment'
        ]);
    }

    /**
     * joy_fan_start_at
     * @return string
     */
    public function getJoyFanStartAtAttribute(): string
    {
        return $this->memberGradeChangeLogs
            ->where('member_grade_id', self::GRADE_JOY_FAN)
            ->first()
            ?->start_at ?? '';
    }

    /**
     * joy_dealer_start_at
     * @return string
     */
    public function getJoyDealerStartAtAttribute(): string
    {
        return $this->memberGradeChangeLogs
            ->where('member_grade_id', self::GRADE_JOY_DEALER)
            ->last()
            ?->start_at ?? '';
    }

    /**
     * children_count
     * @return int
     */
    public function getChildrenCountAttribute(): int
    {
        if (!$this->memberInviteRelation) {
            return 0;
        }

        return $this->memberInviteRelation->descendants(7)->count();
    }

    /**
     * 大頭貼網址 avatar_url
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) return $this->avatar;

        if ($this->gender == 1) {
            return 'https://storage.googleapis.com/joymap-store/default_avatar/default_m_01.png';
        }

        return 'https://storage.googleapis.com/joymap-store/default_avatar/default_f_01.png';
    }

    /**
     * 身分證正面
     * identity_front_url
     */
    public function getIdentityFrontUrlAttribute(): string
    {
        return config('joymap.domain.webapi') . '/v2/member/identity/' . $this->id . '/front';
    }

    /**
     * has_identity_front
     */
    public function getHasIdentityFrontAttribute(): bool
    {
        $res = Http::withHeaders(['domain' => 'admin.joymap.tw'])->get($this->identity_front_url);

        return $res->status() >= 200 && $res->status() < 300;
    }

    /**
     * 身分證背面
     * identity_back_url
     */
    public function getIdentityBackUrlAttribute(): string
    {
        return config('joymap.domain.webapi') . '/v2/member/identity/' . $this->id . '/back';
    }

    /**
     * has_identity_back
     */
    public function getHasIdentityBackAttribute(): bool
    {
        $res = Http::withHeaders(['domain' => 'admin.joymap.tw'])->get($this->identity_back_url);

        return $res->status() >= 200 && $res->status() < 300;
    }

    /**
     * 存摺封面
     * identity_account_url
     */
    public function getIdentityAccountUrlAttribute(): string
    {
        return config('joymap.domain.webapi') . '/v2/member/identity/' . $this->id . '/account';
    }

    /**
     * has_identity_account
     */
    public function getHasIdentityAccountAttribute(): bool
    {
        $res = Http::withHeaders(['domain' => 'admin.joymap.tw'])->get($this->identity_account_url);

        return $res->status() >= 200 && $res->status() < 300;
    }

    /**
     * 電子簽名
     * signature_url
     */
    public function getSignatureUrlAttribute(): string
    {
        return config('joymap.domain.webapi') . '/v2/member/identity/' . $this->id . '/signature';
    }

    /**
     * has_identity_account
     */
    public function getHasSignatureAttribute(): bool
    {
        $res = Http::withHeaders(['domain' => 'admin.joymap.tw'])->get($this->signature_url);

        return $res->status() >= 200 && $res->status() < 300;
    }

    /**
     * 是否有身分證資料
     * has_identity
     * @return bool
     */
    public function getHasIdentityAttribute(): bool
    {
        if ($this->is_foreigner) {
            if (empty($this->passport_number)) {
                return false;
            }
            if (empty($this->ui_number)) {
                return false;
            }
            if (empty($this->foreigner_birthday)) {
                return false;
            }
        } else {
            if (empty($this->id_number)) {
                return false;
            }
        }

        if (!$this->has_identity_front) {
            return false;
        }

        if (!$this->has_identity_back) {
            return false;
        }

        if (!$this->has_identity_account) {
            return false;
        }

        return true;
    }

    /**
     * 取得包含國碼 phone
     * e.g. +886987086921
     * full_phone
     * @return string
     */
    public function getFullPhoneAttribute(): string
    {
        $fullPhone = '+' . $this->phone_prefix . $this->phone;
        try {
            // +8860987086921 ==> +886987086921
            $phone = new PhoneNumber($fullPhone);

            return $phone->formatE164();
        } catch (Throwable $e) {
            Log::error('getFullPhoneAttribute new PhoneNumber error', [
                'data' => $this->toArray(),
                'e' => $e,
            ]);
        }

        return $fullPhone;
    }

    /**
     * login_type
     * @return array
     */
    public function getLoginTypeAttribute(): array
    {
        $loginType = [];

        if (isset($this->password) && !empty($this->password)) {
            $loginType[] = 'general';
        }
        if (isset($this->google_id) && !empty($this->google_id)) {
            $loginType[] = 'google';
        }
        if (isset($this->apple_id) && !empty($this->apple_id)) {
            $loginType[] = 'apple';
        }
        if (isset($this->facebook_id) && !empty($this->facebook_id)) {
            $loginType[] = 'facebook';
        }

        return $loginType;
    }

    public function scopeLotteryDrawNumByType($query, int $lotteryType): int
    {
        return $this->lotteryLogs()
                    ->leftJoin('lotteries', 'lotteries.id', '=', 'lottery_logs.lottery_id')
                    ->where('lottery_logs.status', LotteryLog::STATUS_NOT_DRAWN)
                    ->where('lotteries.type', $lotteryType)
                    ->count();
    }

    public function scopeLotteryDrawChanceByType($query, int $lotteryType): Collection
    {
        return $this->lotteryLogs()
                    ->select('lottery_logs.*')
                    ->leftJoin('lotteries', 'lotteries.id', '=', 'lottery_logs.lottery_id')
                    ->where('lottery_logs.status', LotteryLog::STATUS_NOT_DRAWN)
                    ->where('lotteries.type', $lotteryType)
                    ->orderBy('lottery_logs.type')
                    ->get();
    }
}
