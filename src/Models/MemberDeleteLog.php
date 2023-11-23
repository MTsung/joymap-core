<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDeleteLog extends Model
{
    use HasFactory;

    protected $table = 'member_delete_logs';

    protected $guarded = [];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
