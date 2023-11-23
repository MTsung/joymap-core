<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BroadcastClickLog extends Model
{
    use HasFactory;

    protected $table = 'broadcast_click_logs';

    protected $guarded = [];

    public $timestamps = false;
}
