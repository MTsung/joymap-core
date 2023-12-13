<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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

    public const AVG_PRICE_MIN = [0, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1500, 2000];
    public const AVG_PRICE_MAX = [100, 300, 500, 900, 1000, 1200, 1500, 2000, 2500, 3000, 4000, 5000, 6000, 10000];


    public function restriction()
    {
        return $this->belongsTo(StoreRestriction::class, 'store_restriction_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(StoreRole::class);
    }

    public function notifications()
    {
        return $this->hasMany(StoreNotification::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderSettings()
    {
        return $this->hasOne(OrderSetting::class);
    }

    public function images()
    {
        return $this->hasMany(StoreImage::class);
    }

    public function serviceSettings()
    {
        return $this->hasMany(StoreServiceSetting::class);
    }

    public function replies()
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function users()
    {
        return $this->hasMany(StoreUser::class);
    }

    public function specialBusinessTimes()
    {
        return $this->hasMany(SpecialStoreBusinessTime::class);
    }

    public function businessTimes()
    {
        return $this->hasMany(StoreBusinessTime::class);
    }

    public function storeFoodTypes()
    {
        return $this->hasMany(StoreFoodType::class);
    }

    public function userPasswordValidates()
    {
        return $this->hasMany(StoreUserPasswordValidate::class);
    }

    public function storePayments()
    {
        return $this->hasMany(StorePayment::class);
    }

    public function tags()
    {
        return $this->hasMany(StoreTag::class);
    }

    public function orderHourSettings()
    {
        return $this->hasMany(OrderHourSetting::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function announcementLogs()
    {
        return $this->hasMany(StoreAnnouncementLog::class);
    }

    public function foodTypes()
    {
        return $this->belongsToMany(FoodType::class, 'store_food_types', 'store_id', 'food_type_id');
    }

    public function storeService()
    {
        return $this->belongsToMany(StoreService::class, 'store_service_settings', 'store_id', 'store_service_id')->withPivot('status');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'store_payments', 'store_id', 'payment_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function activeTags()
    {
        return $this->belongsToMany(Tag::class, 'tag_settings');
    }

    public function storeSpgateway()
    {
        return $this->hasOne(StoreSpgateway::class);
    }

    public function ranking()
    {
        return $this->hasOne(Ranking::class);
    }

    public function notificationStorePay()
    {
        return $this->hasOne(NotificationStorePay::class);
    }

    public function storeFloors()
    {
        return $this->hasMany(StoreFloor::class);
    }

    public function storeTableCombinations()
    {
        return $this->hasMany(StoreTableCombination::class);
    }

    public function tables()
    {
        return $this->hasManyThrough(StoreTable::class, StoreFloor::class);
    }

    public function blockOrderHour()
    {
        return $this->hasMany(BlockOrderHour::class);
    }

    public function canOrderTimes()
    {
        return $this->hasMany(CanOrderTime::class);
    }

    public function storeWallet()
    {
        return $this->hasOne(StoreWallet::class);
    }

    public function storeWalletBankSetting()
    {
        return $this->hasOne(StoreWalletBankSetting::class);
    }

    public function lightbox()
    {
        return $this->belongsTo(StoreLightbox::class, 'id', 'store_id');
    }

    // 把座標轉換
    public function newQuery()
    {
        $raw = ' ST_X(lat_lng) as lat, ST_Y(lat_lng) as lng';

        return parent::newQuery()->addSelect('*', DB::raw($raw));
    }

    public function memberDealerRecommendStore()
    {
        return $this->hasOne(MemberDealerRecommendStore::class);
    }

    public function storePayRemindSetting()
    {
        return $this->hasOne(StorePayRemindSetting::class);
    }
}
