<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationMemberRead extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'notification_member_read';

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $guarded = ['id'];

    /* -------------------------------------------------------------------------- */
    /*                                  RELATIONS                                 */
    /* -------------------------------------------------------------------------- */
    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
