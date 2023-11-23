<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';

    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
