<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function storePayments(): HasMany
    {
        return $this->hasMany(StorePayment::class);
    }
}
