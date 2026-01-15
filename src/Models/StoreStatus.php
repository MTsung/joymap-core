<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreStatus extends Model
{
    use HasFactory;

    protected $table = 'store_status';

    protected $guarded = ['id'];

    public function stores(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
