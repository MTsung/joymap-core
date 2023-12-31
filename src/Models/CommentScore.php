<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentScore extends Model
{
    use HasFactory;

    protected $table = 'comment_scores';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(CommentScoreSetting::class, 'comment_score_setting_id');
    }
}
