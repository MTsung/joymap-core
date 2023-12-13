<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreImage extends Model
{
    use HasFactory;

    protected $table = 'store_images';

    public $timestamps = true;

    protected $guarded = ['id'];

    // 首頁圖
    public const TYPE_HOME = 0;
    // logo圖
    public const TYPE_LOGO = 1;
    // 菜單圖
    public const TYPE_MENU = 2;
    // 酒單圖
    public const TYPE_WINE_LIST = 3;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
