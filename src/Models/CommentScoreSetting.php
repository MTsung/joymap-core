<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentScoreSetting extends Model
{
    use HasFactory;

    protected $table = 'comment_score_settings';

    protected $guarded = ['id'];

    public function commentScores(): HasMany
    {
        return $this->hasMany(CommentScore::class);
    }
}
