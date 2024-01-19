<?php

namespace Mtsung\JoymapCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @method Builder notifiable()
 */
class NotificationPlatform extends Model
{
    use HasFactory;

    protected $table = 'notification_platform';

    protected $guarded = ['id'];

    public function getMorphClass(): string
    {
        return $this->getTable();
    }

    public function notify(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notify');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationPlatformLogs::class, 'notification_platform_id', 'id');
    }

    /**
     * notifiable
     * 可通知的訊息
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotifiable(Builder $query): Builder
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        return $query->where('notification_platform.status', 1)
            ->where(function ($q) use ($now) {
                $q->where(function ($q) {
                    $q->whereNull('notification_platform.end_time')
                        ->whereNull('notification_platform.begin_time');
                })->orWhere(function ($q) use ($now) {
                    $q->where('notification_platform.begin_time', '<=', $now)
                        ->where('notification_platform.end_time', '>=', $now);
                });
            });
    }
}
