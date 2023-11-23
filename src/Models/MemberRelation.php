<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberRelation extends Model
{
    use HasFactory;

    protected $table = 'member_relation';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function parentMember()
    {
        return $this->belongsTo(Member::class, 'id', 'parent_member_id');
    }

    public function grandParentMember()
    {
        return $this->belongsTo(Member::class, 'id', 'grand_parent_member_id');
    }
}
