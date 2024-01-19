<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayLog extends Model
{
    protected $table = 'pay_logs';

    protected $guarded = ['id'];

    // 會員刷卡失敗
    public const USER_PAY_STATUS_FAIL = 0;
    // 會員刷卡成功
    public const USER_PAY_STATUS_SUCCESS = 1;
    // 會員無刷卡動作
    public const USER_PAY_STATUS_NO_ACTION = 2;
    // 會員退刷
    public const USER_PAY_STATUS_REFUND = 3;

    // (舊資料)公司卡刷卡失敗、(新資料)儲值金失敗
    public const COMPANY_PAY_STATUS_FAIL = 0;
    // (舊資料)公司卡刷卡成功、(新資料)儲值金成功
    public const COMPANY_PAY_STATUS_SUCCESS = 1;
    // (舊資料)公司卡無刷卡動作、(新資料)儲值金無需更動
    public const COMPANY_PAY_STATUS_NO_ACTION = 2;
    // (舊資料)公司卡退刷、(新資料)儲值金退款
    public const COMPANY_PAY_STATUS_REFUND = 3;

    // 折抵類型 預設
    public const DISCOUNT_TYPE_DEFAULT = 0;
    // 折抵類型 享樂幣折抵
    public const DISCOUNT_TYPE_JCOIN = 1;
    // 折抵類型 享樂經銷券
    public const DISCOUNT_TYPE_COUPON = 2;

    // JOYMAP
    public const FROM_SOURCE_JOYMAP = 0;
    // TWDD
    public const FROM_SOURCE_TWDD = 1;

    // 有效的用戶支付狀態
    public const EFFECTIVE_USER_PAY_STATUS = [
        self::USER_PAY_STATUS_SUCCESS,
        self::USER_PAY_STATUS_NO_ACTION,
    ];

    /**
     * 要抓公司卡才會需要用這個
     */
    public function creditCardLogs(): BelongsToMany
    {
        return $this->belongsToMany(CreditCardLog::class, 'pay_credit_logs', 'pay_log_id', 'creditcard_log_id');
    }

    /**
     * 有 pay_log_id 的代表是刷 member 卡
     */
    public function memberCreditCardLog(): HasMany
    {
        return $this->hasMany(CreditCardLog::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function coinLog(): HasMany
    {
        return $this->hasMany(CoinLog::class, 'pay_log_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'pay_log_id', 'id');
    }

    public function couponNumberTransactionLogs(): HasMany
    {
        return $this->hasMany(CouponNumberTransactionLog::class, 'pay_log_id');
    }

    /**
     * 判斷是否有修改權
     * @param Authenticatable $user
     * @return bool
     */
    public function isOwns(Authenticatable $user): bool
    {
        if ($user instanceof StoreUser) {
            return $this->store_id == $user->store_id;
        }

        if ($user instanceof Member) {
            return $this->member_id == $user->id;
        }

        if ($user instanceof AdminUser) {
            return true;
        }

        return false;
    }
}
