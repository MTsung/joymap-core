<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentImage extends Model
{
    use HasFactory;

    protected $table = 'comment_images';

    protected $guarded = [];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
