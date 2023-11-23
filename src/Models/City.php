<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $table = "cities";

    protected $guarded = [];

    public $timestamps = false;

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
