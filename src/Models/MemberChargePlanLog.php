<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string type_text
 */
class MemberChargePlanLog extends Model
{
    use HasFactory;

    protected $table = "member_charge_plan_log";

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $appends = ['type_text'];

    // 申請時間
    public const TYPE_APPLY_TIME = 0;
    // 寄送時間
    public const TYPE_SHIPPING_TIME = 1;
    // 確認時間
    public const TYPE_CONFIRM_TIME = 2;

    public function memberChargePlan(): BelongsTo
    {
        return $this->belongsTo(MemberChargePlan::class);
    }

    /**
     * type_text
     * @return string
     */
    public function getTypeTextAttribute(): string
    {
        return __('member_charge_plan_log.type.' . $this->type);
    }
}
