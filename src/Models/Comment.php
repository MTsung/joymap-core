<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $guarded = [];

    // 無
    public const SCORE_NONE = 0;
    // 糟透了
    public const SCORE_TERRIBLE = 1;
    // 微差
    public const SCORE_POOR = 2;
    // 一般
    public const SCORE_AVERAGE = 3;
    // 滿好
    public const SCORE_GOOD = 4;
    // 非常棒
    public const SCORE_EXCELLENT = 5;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pay()
    {
        return $this->belongsTo(PayLog::class, 'pay_log_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeReplies()
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function scores()
    {
        return $this->hasMany(CommentScore::class);
    }

    public function images()
    {
        return $this->hasMany(CommentImage::class);
    }
}
