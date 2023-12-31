<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method active()
 */
class SystemTask extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function logs(): HasMany
    {
        return $this->hasMany(SystemTaskLog::class, 'system_task_id', 'id');
    }

    public function coinLogs(): HasMany
    {
        return $this->hasMany(CoinLog::class, 'system_task_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
