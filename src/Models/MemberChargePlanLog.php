<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function memberChargePlan()
    {
        return $this->belongsTo(MemberChargePlan::class);
    }

    public function getTypeTextAttribute()
    {
        return __('member_charge_plan_log.type.' . $this->type);
    }
}
