<?php

namespace Mtsung\JoymapCore\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $guarded = ['id'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
