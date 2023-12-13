<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleMark extends Model
{
    use HasFactory;

    protected $table = "article_mark";

    protected $guarded = ['id'];

    public $timestamps = false;

}
