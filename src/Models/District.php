<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    protected $table = "districts";

    protected $guarded = ['id'];

    public $timestamps = false;

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
