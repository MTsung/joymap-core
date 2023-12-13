<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
