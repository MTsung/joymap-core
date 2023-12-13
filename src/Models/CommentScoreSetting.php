<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentScoreSetting extends Model
{
    use HasFactory;

    protected $table = 'comment_score_settings';

    protected $guarded = ['id'];

    public function commentScores()
    {
        return $this->hasMany(CommentScore::class);
    }
}
