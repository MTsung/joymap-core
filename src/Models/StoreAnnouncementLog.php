<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreAnnouncementLog extends Model
{
    use HasFactory;

    protected $table = 'store_announcement_logs';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(StoreAnnouncement::class);
    }
}
