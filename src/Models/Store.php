<?php

namespace Mtsung\JoymapCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Services\Store\GetBusinessTimeService;
use stdClass;

/**
 * @property string full_address
 * @property string logo_url
 * @property string banner_url
 * @property int limit_minute
 * @property string food_type_full_name
 * @property int business_status_now
 * @property string business_status_now_text
 * @property stdClass business_time_week
 * @property bool is_hot
 * @property bool is_new
 * @property bool can_use_designated_driver
 * @property string booking_url
 *
 * @method Builder foodTypeIn(array $ids)
 * @method Builder foodTypeLikeName(string $name)
 * @method Builder activeTagsIn(array $ids)
 * @method Builder whereAccount(string $account)
 * @method Builder whereVerifyCode(string $code)
 * @method Builder withWhereHas($relation, $constraint)
 */
class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $hidden = ['lat_lng'];

    // 停用
    public const CAN_PAY_DISABLED = 0;
    // 啟用
    public const CAN_PAY_ENABLED = 1;
    // 未啟用
    public const CAN_PAY_NOT_ENABLED = 2;
    // 未合作
    public const CAN_PAY_NOT_COOPERATING = 3;

    // 停用
    public const CAN_ORDER_DISABLED = 0;
    // 啟用
    public const CAN_ORDER_ENABLED = 1;
    // 未啟用
    public const CAN_ORDER_NOT_ENABLED = 2;
    // 未合作
    public const CAN_ORDER_NOT_COOPERATING = 3;

    // 停用
    public const CAN_COMMENT_DISABLED = 0;
    // 啟用
    public const CAN_COMMENT_ENABLED = 1;

    // 下架
    public const STATUS_OFF_SHELF = 0;
    // 上架
    public const STATUS_ON_SHELF = 1;
    // 待上架
    public const STATUS_PENDING = 2;

    // 歇業
    public const IS_ACTIVE_CLOSED = 0;
    // 營業
    public const IS_ACTIVE_OPEN = 1;

    // 失敗
    public const IS_BANK_CHECK_DEFAULT = 0;
    // 審核中
    public const IS_BANK_CHECK_UNDER_REVIEW = 1;
    // 審核通過
    public const IS_BANK_CHECK_PASSED = 2;
    // 審核失敗
    public const IS_BANK_CHECK_FAILED_REVIEW = 3;

    // 未綁定推薦店家
    public const IS_BIND_DEALER_RECOMMEND_OFF = 0;
    // 已綁定推薦店家
    public const IS_BIND_DEALER_RECOMMEND_ON = 1;

    // JOYMAP
    public const FROM_SOURCE_JOYMAP = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;

    // (不是 Table 的欄位)
    // 公休
    public const BUSINESS_STATUS_NOW_CLOSED = 0;
    // 營業中
    public const BUSINESS_STATUS_NOW_OPEN = 1;
    // 休息中
    public const BUSINESS_STATUS_NOW_RESTING = 2;

    // 預設的店家來源(目前預設為享樂地圖)
    public const FROM_SOURCE_DEFAULT = 0;

    // 預設的店家營業狀況(目前預設為營業中)
    public const iS_ACTIVE_DEFAULT = 1;
    public const AVG_PRICE_MIN = [0, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1500, 2000];
    public const AVG_PRICE_MAX = [100, 300, 500, 900, 1000, 1200, 1500, 2000, 2500, 3000, 4000, 5000, 6000, 10000];

    // 把座標轉換
    public function newQuery(): Builder
    {
        return parent::newQuery()->addSelect([
            'stores.*',
            DB::raw('ST_X(stores.lat_lng) as lat, ST_Y(stores.lat_lng) as lng'),
            DB::raw('CONCAT(ST_X(stores.lat_lng), ",", ST_Y(stores.lat_lng)) as lat_lng'),
        ]);
    }

    public function restriction(): BelongsTo
    {
        return $this->belongsTo(StoreRestriction::class, 'store_restriction_id', 'id');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(StoreRole::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(StoreNotification::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payLogs(): HasMany
    {
        return $this->hasMany(PayLog::class);
    }

    public function orderSettings(): HasOne
    {
        return $this->hasOne(OrderSetting::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(StoreImage::class);
    }

    public function serviceSettings(): HasMany
    {
        return $this->hasMany(StoreServiceSetting::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(StoreUser::class);
    }

    public function specialBusinessTimes(): HasMany
    {
        return $this->hasMany(SpecialStoreBusinessTime::class);
    }

    public function businessTimes(): HasMany
    {
        return $this->hasMany(StoreBusinessTime::class);
    }

    public function storeFoodTypes(): HasMany
    {
        return $this->hasMany(StoreFoodType::class);
    }

    public function userPasswordValidates(): HasMany
    {
        return $this->hasMany(StoreUserPasswordValidate::class);
    }

    public function storePayments(): HasMany
    {
        return $this->hasMany(StorePayment::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(StoreTag::class);
    }

    public function orderHourSettings(): HasMany
    {
        return $this->hasMany(OrderHourSetting::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function announcementLogs(): HasMany
    {
        return $this->hasMany(StoreAnnouncementLog::class);
    }

    public function foodTypes(): BelongsToMany
    {
        return $this->belongsToMany(FoodType::class, 'store_food_types', 'store_id', 'food_type_id');
    }

    public function mainFoodType(): BelongsTo
    {
        return $this->belongsTo(MainFoodType::class);
    }

    public function storeService(): BelongsToMany
    {
        return $this->belongsToMany(StoreService::class, 'store_service_settings', 'store_id', 'store_service_id')->withPivot('status');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'store_payments', 'store_id', 'payment_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function activeTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_settings');
    }

    public function storeSpgateway(): HasOne
    {
        return $this->hasOne(StoreSpgateway::class);
    }

    public function ranking(): HasOne
    {
        return $this->hasOne(Ranking::class);
    }

    public function notificationStorePay(): HasOne
    {
        return $this->hasOne(NotificationStorePay::class);
    }

    public function storeFloors(): HasMany
    {
        return $this->hasMany(StoreFloor::class);
    }

    public function storeTableCombinations(): HasMany
    {
        return $this->hasMany(StoreTableCombination::class);
    }

    public function tables(): HasManyThrough
    {
        return $this->hasManyThrough(StoreTable::class, StoreFloor::class);
    }

    public function blockOrderHour(): HasMany
    {
        return $this->hasMany(BlockOrderHour::class);
    }

    public function canOrderTimes(): HasMany
    {
        return $this->hasMany(CanOrderTime::class);
    }

    public function storeWallet(): HasOne
    {
        return $this->hasOne(StoreWallet::class);
    }

    public function storeWalletBankSetting(): HasOne
    {
        return $this->hasOne(StoreWalletBankSetting::class);
    }

    public function lightbox(): BelongsTo
    {
        return $this->belongsTo(StoreLightbox::class, 'id', 'store_id');
    }

    public function memberDealerRecommendStore(): HasOne
    {
        return $this->hasOne(MemberDealerRecommendStore::class);
    }

    public function storePayRemindSetting(): HasOne
    {
        return $this->hasOne(StorePayRemindSetting::class);
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_logs');
    }

    public function workingActivities(): BelongsToMany
    {
        return $this->activities()->where('activities.end_time', '>', Carbon::now());
    }

    public function joyPayStoreSetting(): hasOne
    {
        return $this->hasOne(JoyPayStoreSetting::class);
    }

    public function storeSubscription(): HasOne
    {
        return $this->hasOne(StoreSubscription::class);
    }

    public function storeSubscriptionPeriod(): HasMany
    {
        return $this->hasMany(StoreSubscriptionPeriod::class);
    }

    public function storeMonthCount(): HasMany
    {
        return $this->hasMany(StoreMonthCount::class);
    }

    public function nowStoreSubscriptionPeriod(): HasMany
    {
        return $this->storeSubscriptionPeriod()
            ->where('store_subscription_period.status', 1)
            ->where('store_subscription_period.type', StoreSubscriptionPeriod::TYPE_SYSTEM)
            ->where('store_subscription_period.period_start_at', '<=', Carbon::now())
            ->where('store_subscription_period.period_end_at', '>=', Carbon::now());
    }

    public function storeCreditCard(): HasOne
    {
        return $this->hasOne(StoreCreditCard::class);
    }

    public function storeOrderBlacklist(): HasMany
    {
        return $this->hasMany(StoreOrderBlacklist::class);
    }

    public function storeRecommender(): BelongsTo
    {
        return $this->belongsTo(StoreRecommender::class);
    }

    public function designatedDriverMatch(): HasMany
    {
        return $this->hasMany(DesignatedDriverMatch::class);
    }

    public function designatedDriverTwdd(): HasMany
    {
        return $this->hasMany(DesignatedDriverTwdd::class);
    }

    public function serviceActivity(): HasOne
    {
        return $this->hasOne(ServiceActivity::class);
    }

    public function serviceCategories(): HasMany
    {
        return $this->hasMany(ServiceCategory::class);
    }

    // foodTypeIn($ids)
    public function scopeFoodTypeIn(Builder $query, array $ids): Builder
    {
        return $query->whereHas('foodTypes', fn($q) => $q->whereIn('id', $ids));
    }

    // foodTypeLikeName($ids)
    public function scopeFoodTypeLikeName(Builder $query, string $name): Builder
    {
        return $query->whereHas('foodTypes', fn($q) => $q->where('name', 'like', '%' . $name . '%'));
    }

    // activeTagsIn($ids)
    public function scopeActiveTagsIn(Builder $query, array $ids): Builder
    {
        return $query->whereHas('activeTags', fn($q) => $q->whereIn('id', $ids));
    }

    // whereAccount
    public function scopeWhereAccount($query, string $account): Builder
    {
        return $query->withWhereHas(
            'users',
            fn($query) => $query->where('account', $account)
        );
    }

    // whereVerifyCode
    public function scopeWhereVerifyCode($query, string $code): Builder
    {
        return $query->withWhereHas(
            'userPasswordValidates',
            fn($query) => $query->where('code', $code)
        );
    }

    // withWhereHas
    public function scopeWithWhereHas($query, $relation, $constraint): Builder
    {
        return $query->whereHas($relation, $constraint)->with([$relation => $constraint]);
    }

    /**
     * 取得完整地址
     * full_address
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $this->loadMissing([
            'city',
            'district',
        ]);

        return $this->city->name . $this->district->name . $this->address;
    }

    /**
     * 取得店家 Logo 網址
     * logo_url
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        $image = $this->images->where('type', StoreImage::TYPE_LOGO)->first();

        return $image?->url ??
            'https://storage.googleapis.com/joymap-store/logo/joymap_store_logo.png';
    }

    /**
     * 取得店家第一張 Banner 網址
     * banner_url
     * @return string
     */
    public function getBannerUrlAttribute(): string
    {
        $image = $this->images->where('type', StoreImage::TYPE_HOME)->first();

        return $image?->url ?? '';
    }

    /**
     * 取得店家用餐時間
     * limit_minute
     * @return int
     */
    public function getLimitMinuteAttribute(): int
    {
        return $this->restriction?->limit_minute ?? 120;
    }

    /**
     * 取得次分類完整名稱
     * food_type_full_name
     * @return string
     */
    public function getFoodTypeFullNameAttribute(): string
    {
        return $this->foodTypes->implode('name', '・');
    }

    /**
     * is_hot
     * 是否熱門
     * @return bool
     */
    public function getIsHotAttribute(): bool
    {
        return isset($this->ranking);
    }

    /**
     * is_new
     * 是否為新上架(一個月內)
     * @return bool
     */
    public function getIsNewAttribute(): bool
    {
        if (!isset($this->put_at)) {
            return false;
        }

        return Carbon::create($this->put_at) >= Carbon::now()->subMonth();
    }

    /**
     * business_status_now
     * 目前營業狀況，與 is_open 不同的是多了一個 休息中狀態
     *
     * @return int 0=關店, 1=營業, 2=休息
     */
    public function getBusinessStatusNowAttribute(): int
    {
        $now = Carbon::now();

        $businessTime = Cache::remember(
            __METHOD__ . 'GetBusinessTimeService' . $this->id,
            60,
            fn() => GetBusinessTimeService::run($this)
        );

        if ($businessTime->where('begin_time', '<=', $now)->where('end_time', '>=', $now)->isNotEmpty()) {
            return self::BUSINESS_STATUS_NOW_OPEN;
        }

        if ($businessTime->where('date', $now->toDateString())->where('begin_time', '>', $now)->isNotEmpty()) {
            return self::BUSINESS_STATUS_NOW_RESTING;
        }

        return self::BUSINESS_STATUS_NOW_CLOSED;
    }


    /**
     * business_status_now_text
     * 根據狀態顯示 營業結束時間 OR 下次營業時間 OR 下次營業日期
     *
     * @return string
     */
    public function getBusinessStatusNowTextAttribute(): string
    {
        $now = Carbon::now();

        $businessTime = Cache::remember(
            __METHOD__ . 'GetBusinessTimeService' . $this->id,
            60,
            fn() => GetBusinessTimeService::run($this)
        );

        switch ($this->getBusinessStatusNowAttribute()) {
            case self::BUSINESS_STATUS_NOW_OPEN:
                $bs = $businessTime->where('begin_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->first();

                return Carbon::parse($bs['end_time'])->toTimeString();
            case self::BUSINESS_STATUS_NOW_RESTING:
                $bs = $businessTime->where('date', $now->toDateString())
                    ->where('begin_time', '>', $now)
                    ->first();

                return Carbon::parse($bs['begin_time'])->toTimeString();
            case self::BUSINESS_STATUS_NOW_CLOSED:
                if (!$bs = $businessTime->where('begin_time', '>', $now)->first()) {
                    return '';
                }

                return Carbon::parse($bs['begin_time'])->format('m月d日');
        }

        return '';
    }

    /**
     * business_time_week
     * 取得這七天的營業時間陣列
     *
     * @return stdClass
     */
    public function getBusinessTimeWeekAttribute(): stdClass
    {
        $res = (object)[[], [], [], [], [], [], []];

        $startDate = Carbon::today()->toDateString();
        $endDate = Carbon::today()->addDays(6)->toDateString();
        $specialBusinessTime = $this->specialBusinessTimes()->whereBetween('special_date', [$startDate, $endDate])->get();

        $businessTimes = $this->businessTimes;

        foreach ($res as $week => $value) {
            $specialTimesForWeek = $specialBusinessTime->where('week', $week);

            if ($specialTimesForWeek->isEmpty()) {
                $res->$week = $businessTimes->where('week', $week)->where('is_open', 1);
            } else {
                $res->$week = $specialTimesForWeek->where('is_open', 1);
            }

            $res->$week = collect($res->$week)->map(function ($v) {
                return [
                    'begin_time' => Carbon::parse($v['begin_time'])->format('H:i'),
                    'end_time' => Carbon::parse($v['end_time'])->format('H:i'),
                ];
            })->values();
        }

        return $res;
    }


    /**
     * can_use_designated_driver
     * 是否可使用代駕功能
     *
     * @return bool
     */
    public function getCanUseDesignatedDriverAttribute(): bool
    {
        if (($this->nowStoreSubscriptionPeriod()->first()?->store_plan_id ?? 0) > 1) {
            return true;
        }

        return $this->storeSubscriptionPeriod()
            ->where('store_subscription_period.store_plan_id', StorePlan::ID_DESIGNATED_DRIVER)
            ->where('store_subscription_period.status', 1)
            ->where('store_subscription_period.type', StoreSubscriptionPeriod::TYPE_ADD)
            ->where('store_subscription_period.period_start_at', '<=', Carbon::now())
            ->where('store_subscription_period.period_end_at', '>=', Carbon::now())
            ->exists();
    }

    /**
     * booking_url
     * @return string
     */
    public function getBookingUrlAttribute(): string
    {
        if ($this->main_food_type_id == MainFoodType::ID_FOOD){
            return config('joymap.domain.www') . '/store/' . $this->slug;
        }

        return config('joymap.domain.www') . '/life_store/#/' . $this->slug;
    }
}
