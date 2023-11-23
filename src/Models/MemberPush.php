<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberPush extends Model
{
    use HasFactory;

    protected $table = 'member_push';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
