<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JcUser extends Model
{
    use HasFactory;

    protected $table = "jc_users";

    protected $guarded = ['id'];

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }
}
