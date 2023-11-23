<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberCollection extends Model
{
    use HasFactory;

    protected $table = 'member_collection';

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
