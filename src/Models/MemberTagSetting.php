<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberTagSetting extends Model
{
    use HasFactory;

    protected $table = 'member_tag_settings';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function storeTag()
    {
        return $this->belongsTo(StoreTag::class);
    }
}
