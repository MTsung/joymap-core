<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreWalletBankSetting extends Model
{
    use HasFactory;

    protected $table = 'store_wallet_bank_settings';

    protected $guarded = ['id'];

    public function storeWallet(): BelongsTo
    {
        return $this->belongsTo(StoreWallet::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
