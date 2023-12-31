<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';

    protected $guarded = ['id'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
