<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property string full_address
 * @property string logo_url
 * @property int limit_minute
 * @property string food_type_full_name
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

        return $this->district->name . $this->city->name . $this->address;
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
}
