<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignatedDriverTwdd extends Model
{
    use HasFactory;

    protected $table = 'designated_driver_twdd';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function designatedDriverMatch(): BelongsTo
    {
        return $this->belongsTo(DesignatedDriverMatch::class);
    }
}
