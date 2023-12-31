<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreTag extends Model
{
    use HasFactory;

    protected $table = 'store_tags';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function memberTagSettings(): HasMany
    {
        return $this->hasMany(MemberTagSetting::class);
    }

    public function orderTagSettings(): HasMany
    {
        return $this->hasMany(OrderTagSetting::class);
    }
}
