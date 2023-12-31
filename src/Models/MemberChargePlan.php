<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberChargePlan extends Model
{
    use HasFactory;

    protected $table = "member_charge_plan";

    public $timestamps = false;

    protected $guarded = ['id'];

    // 待處理
    public const STATUS_PENDING = 0;
    // 寄送中
    public const STATUS_IN_TRANSIT = 1;
    // 已確認
    public const STATUS_CONFIRMED  = 2;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function chargePlan(): BelongsTo
    {
        return $this->belongsTo(ChargePlan::class);
    }

    public function memberChargePlanLogs(): HasMany
    {
        return $this->hasMany(MemberChargePlanLog::class);
    }
}
