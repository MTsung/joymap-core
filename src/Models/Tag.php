<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $table = "tags";

    protected $guarded = [];

    public function settings()
    {
        return $this->hasMany(TagSetting::class, 'tag_id', 'id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'tag_settings');
    }
}
