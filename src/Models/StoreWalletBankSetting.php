<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreWalletBankSetting extends Model
{
    use HasFactory;

    protected $table = 'store_wallet_bank_settings';

    protected $guarded = [];

    public function storeWallet()
    {
        return $this->belongsTo(StoreWallet::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
