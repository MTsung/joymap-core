<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class NotificationNewRegister extends Model
{
    use HasFactory;

    protected $table = 'notification_new_register';

    protected $guarded = ['id'];

    public function getMorphClass(): string
    {
        return $this->getTable();
    }

    public function notify(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notify');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
