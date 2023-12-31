<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberRelation extends Model
{
    use HasFactory;

    protected $table = 'member_relation';

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function parentMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id', 'parent_member_id');
    }

    public function grandParentMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id', 'grand_parent_member_id');
    }
}
