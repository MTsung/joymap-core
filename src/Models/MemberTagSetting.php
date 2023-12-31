<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberTagSetting extends Model
{
    use HasFactory;

    protected $table = 'member_tag_settings';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function storeTag(): BelongsTo
    {
        return $this->belongsTo(StoreTag::class);
    }
}
