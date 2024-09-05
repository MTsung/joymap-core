<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorePlanSetting extends Model
{
    protected $table = 'store_plan_setting';

    protected $guarded = ['id'];

    public function storePlan(): BelongsTo
    {
        return $this->belongsTo(StorePlan::class);
    }
}
