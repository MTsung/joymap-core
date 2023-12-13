<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberGrade extends Model
{
    use HasFactory;

    protected $table = 'member_grades';

    protected $guarded = ['id'];

}
