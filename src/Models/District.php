<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $table = "districts";

    protected $guarded = ['id'];

    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
