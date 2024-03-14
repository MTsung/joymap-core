<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberWithdrawApplicationForm extends Model
{
    use HasFactory;

    protected $table = "member_withdraw_application_forms";
    protected $guarded = [];

    //資料未驗證過
    const IS_ACTIVE_OFF = 0;
    //資料已驗證
    const IS_ACTIVE_ON = 1;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}