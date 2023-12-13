<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberCreditCard extends Model
{
    use HasFactory;

    protected $table = 'member_credit_card';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
