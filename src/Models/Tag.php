<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = "tags";

    protected $guarded = ['id'];

    public function settings(): HasMany
    {
        return $this->hasMany(TagSetting::class, 'tag_id', 'id');
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'tag_settings');
    }
}
