<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ranking extends Model
{
    use HasFactory;

    protected $table = 'ranking';

    public $timestamps = true;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
