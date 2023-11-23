<?php

namespace Mtsung\JoymapCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPlatform extends Model
{
    use HasFactory;

    protected $table = 'notification_platform';

    protected $guarded = [];

    public function getMorphClass()
    {
        return $this->getTable();
    }

    public function notify()
    {
        return $this->morphOne(Notification::class, 'notify');
    }

    public function logs()
    {
        return $this->hasMany(NotificationPlatformLogs::class, 'notification_platform_id', 'id');
    }

    /**
     * notifiable
     * 可通知的訊息
     * @param $query
     * @return mixed
     */
    public function scopeNotifiable($query)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        return $query->where('status', 1)
            ->where(function ($q) use ($now) {
                $q->where(function ($q) {
                    $q->whereNull('end_time')
                        ->whereNull('begin_time');
                })->orWhere(function ($q) use ($now) {
                    $q->where('begin_time', '<=', $now)
                        ->where('end_time', '>=', $now);
                });
            });
    }
}
