<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentImage extends Model
{
    use HasFactory;

    protected $table = 'comment_images';

    protected $guarded = ['id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
