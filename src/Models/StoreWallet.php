<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreWallet extends Model
{
    use HasFactory;

    protected $table = 'store_wallets';

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeWalletBankSetting()
    {
        return $this->hasOne(StoreWalletBankSetting::class);
    }

    public function storeWalletTransactionRecords()
    {
        return $this->hasMany(StoreWalletTransactionRecord::class);
    }
}
