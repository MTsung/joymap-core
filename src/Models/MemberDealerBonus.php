<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDealerBonus extends Model
{
    use HasFactory;

    protected $guarded = [];

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
    // 忽略
    public const STATUS_NEGLECT = 99;

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
