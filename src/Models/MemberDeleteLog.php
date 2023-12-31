<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDeleteLog extends Model
{
    use HasFactory;

    protected $table = 'member_delete_logs';

    protected $guarded = ['id'];


    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
