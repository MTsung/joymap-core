<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberGradeChangeLog extends Model
{
    use HasFactory;

    protected $table = 'member_grade_change_logs';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function memberGrade()
    {
        return $this->belongsTo(MemberGrade::class);
    }
}
