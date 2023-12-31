<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreWalletWithdraw extends Model
{
    use HasFactory;

    protected $table = 'store_wallet_withdraws';

    protected $guarded = ['id'];

    public function storeWalletTransactionRecord(): BelongsTo
    {
        return $this->belongsTo(StoreWalletTransactionRecord::class);
    }

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
