<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreWalletTransactionRecord  extends Model
{
    use HasFactory;

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
