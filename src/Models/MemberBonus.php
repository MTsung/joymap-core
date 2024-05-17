<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int money
 */
class MemberBonus extends Model
{
    use HasFactory;

    protected $table = 'member_bonus';

    protected $guarded = ['id'];

    // 排程中
    public const STATUS_SCHEDULED = 0;
    // 發放中
    public const STATUS_ONGOING = 1;
    // 已發放
    public const STATUS_COMPLETED = 2;
    // 失敗
    public const STATUS_FAILED = 3;
    // 未達標
    public const STATUS_NOT_REACHED = 4;
    // 退刷
    public const STATUS_REFUNDED = 5;
    // 不是天使 4～7 層
    public const STATUS_SKIP = 99;

    protected static function booted()
    {
        static::addGlobalScope('skip_status_mothball', function ($builder) {
            $builder->where('member_bonus.status', '!=', self::STATUS_SKIP);
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class, 'pay_log_id', 'id');
    }
}
