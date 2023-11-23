<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorePayment extends Model
{
    use HasFactory;

    protected $table = 'store_payments';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
