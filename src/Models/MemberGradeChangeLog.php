<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberGradeChangeLog extends Model
{
    use HasFactory;

    protected $table = 'member_grade_change_logs';

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberGrade(): BelongsTo
    {
        return $this->belongsTo(MemberGrade::class);
    }
}
