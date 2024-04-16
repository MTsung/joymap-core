<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberGrade extends Model
{
    use HasFactory;

    protected $table = 'member_grades';

    protected $guarded = ['id'];

    // 一般會員
    public const TYPE_GENERAL = 0;
    // 樂粉會員
    public const TYPE_JOY_FAN = 1;
    // 天使會員
    public const TYPE_JOY_ANGEL = 2;
    // 大天使會員
    public const TYPE_JOY_ARCHANGEL = 3;
    // 熾天使會員
    public const TYPE_JOY_SERAPH = 4;
}
