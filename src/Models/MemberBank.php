<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberBank extends Model
{
    use HasFactory;

    protected $table = "member_banks";
    protected $guarded = ['id'];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
