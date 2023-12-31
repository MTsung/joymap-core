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
