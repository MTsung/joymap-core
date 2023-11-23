<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    public $timestamps = true;

    protected $guarded = [];

    public function storePayments()
    {
        return $this->hasMany(StorePayment::class);
    }
}
