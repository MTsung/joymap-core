<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $table = "articles";

    protected $guarded = ['id'];

    // 熱門(否)
    public const IS_HOT_NO = 0;
    // 熱門(是)
    public const IS_HOT_YES = 1;

    // 下架
    public const STATUS_OFF_SHELF = 0;
    // 上架
    public const STATUS_ON_SHELF = 1;
    // 草稿
    public const STATUS_DRAFT = 2;


    public function articleMark(): HasMany
    {
        return $this->hasMany(ArticleMark::class);
    }
}
