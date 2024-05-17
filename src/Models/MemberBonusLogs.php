<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int money
 */
class MemberBonusLogs extends Model
{
    use HasFactory;

    protected $table = 'member_bonus_logs';

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function subscriptionProgramOrder(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProgramOrder::class);
    }
}
