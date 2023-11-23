<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Carousel extends Model
{
    use HasFactory;

    protected $table = 'carousel';

    protected $guarded = [];

    public $timestamps = true;

    // 下架
    public const STATUS_OFF_SHELF = 0;
    // 上架
    public const STATUS_ON_SHELF = 1;

    // open_type
    public function getOpenTypeAttribute()
    {
        return Str::contains($this->url, '/member/news/');
    }
}
