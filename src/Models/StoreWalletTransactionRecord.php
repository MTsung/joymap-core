<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreWalletTransactionRecord extends Model
{
    use HasFactory;

    // 享樂折抵
    const TYPE_DISCOUNT = 1;
    // 商家提領
    const TYPE_STORE_WITHDRAW = 2;
    // 平台抽成
    const TYPE_PLATFORM_COMMISSION = 3;
    // 退刷回收
    const TYPE_REFUND = 4;
    // 其他異動
    const TYPE_OTHER_CHANGES = 5;
    // 活動回饋
    const TYPE_ACTIVITY_FEEDBACK = 6;
    // 代駕回饋金
    const TYPE_DRIVER_FEEDBACK = 7;

    // 處理中
    const STATUS_PROCESSING = 0;
    // 處理成功
    const STATUS_SUCCESS = 1;
    // 處理失敗
    const STATUS_FAILURE = 2;

    protected $table = 'store_wallet_transaction_records';

    protected $guarded = ['id'];

    public function storeWallet()
    {
        return $this->belongsTo(StoreWallet::class);
    }

    public function storeWalletWithdraw()
    {
        return $this->hasOne(StoreWalletWithdraw::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog()
    {
        return $this->belongsTo(PayLog::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class);
    }

    public function storeUser()
    {
        return $this->belongsTo(StoreUser::class);
    }
}
