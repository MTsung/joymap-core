<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BroadcastLog extends Model
{
    use HasFactory;

    protected $table = 'broadcast_logs';

    protected $guarded = ['id'];

    public $timestamps = true;

    public function clicks()
    {
        return $this->hasMany(BroadcastClickLog::class);
    }
}
