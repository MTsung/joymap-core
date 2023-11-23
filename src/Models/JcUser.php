<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JcUser extends Model
{
    use HasFactory;

    protected $table = "jc_users";

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
