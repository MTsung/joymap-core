<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreAnnouncementLog extends Model
{
    use HasFactory;

    protected $table = 'store_announcement_logs';

    public $timestamps = true;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function announcement()
    {
        return $this->belongsTo(StoreAnnouncement::class);
    }
}
