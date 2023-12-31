<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StoreWallet extends Model
{
    use HasFactory;

    protected $table = 'store_wallets';

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeWalletBankSetting(): HasOne
    {
        return $this->hasOne(StoreWalletBankSetting::class);
    }

    public function storeWalletTransactionRecords(): HasMany
    {
        return $this->hasMany(StoreWalletTransactionRecord::class);
    }
}
