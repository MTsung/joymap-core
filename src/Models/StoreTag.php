<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTag extends Model
{
    use HasFactory;

    protected $table = 'store_tags';

    public $timestamps = true;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function memberTagSettings()
    {
        return $this->hasMany(MemberTagSetting::class);
    }

    public function orderTagSettings()
    {
        return $this->hasMany(OrderTagSetting::class);
    }
}
