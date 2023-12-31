<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChargePlan extends Model
{
    use HasFactory;

    protected $table = "charge_plan";

    protected $guarded = ['id'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_charge_plan', 'charge_plan_id', 'member_id')->withPivot('created_at');
    }
}
