<?php

namespace Mtsung\JoymapCore\Models;


class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
