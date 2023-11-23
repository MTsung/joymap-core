<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemTaskLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function task(): BelongsTo
    {
        return $this->belongsTo(SystemTask::class, 'system_task_id', 'id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
