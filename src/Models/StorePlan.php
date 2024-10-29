<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StorePlan extends Model
{
    protected $table = 'store_plan';

    protected $guarded = ['id'];

    // 系統訂閱
    public const TYPE_SUBSCRIPTION = 0;
    // 加價
    public const TYPE_ADD = 1;

    // 入門
    public const ID_ENTRY_LEVEL = 1;
    // 行家
    public const ID_EXPERT = 2;
    // 豪華
    public const ID_LUXURY = 3;
    // 減碳
    public const ID_CARBON_REDUCTION = 4;
    // 代駕
    public const ID_DESIGNATED_DRIVER = 5;
    // 短影音服務
    public const ID_VIDEO = 6;

    public function storeSubscription(): HasMany
    {
        return $this->hasMany(StoreSubscription::class);
    }

    public function storePlanSetting(): HasOne
    {
        return $this->hasOne(StorePlanSetting::class);
    }

    public function storeSubscriptionAddPlan(): HasMany
    {
        return $this->hasMany(StoreSubscriptionAddPlan::class);
    }
}
