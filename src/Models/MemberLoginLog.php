<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberLoginLog extends Model
{
    use HasFactory;

    protected $table = 'member_login_log';

    protected $guarded = ['id'];

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $casts = [
        'origin_request_header' => 'array'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
