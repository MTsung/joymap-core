<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreWalletWithdraw extends Model
{
    use HasFactory;

    protected $table = 'store_wallet_withdraws';

    protected $guarded = [];

    public function storeWalletTransactionRecord()
    {
        return $this->belongsTo(StoreWalletTransactionRecord::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
