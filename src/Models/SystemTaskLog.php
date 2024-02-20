<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemTaskLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    // 新註冊獎勵
    public const TASK_ID_REGISTER_REWARD = 1;
    // 開通天使紅利
    public const TASK_ID_ACTIVATE_JOY_FAN_CASHBACK_SYSTEM = 2;
    // 群悅春酒獎金
    public const TASK_ID_SPRING_FEAST_BONUS = 3;
    // 群悅春酒遊戲獎金
    public const TASK_ID_GAME_FEAST_BONUS = 4;
    // 問券填寫活動獎勵
    public const TASK_ID_SURVEY_ACTIVITY_REWARD = 5;

    public function task(): BelongsTo
    {
        return $this->belongsTo(SystemTask::class, 'system_task_id', 'id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
