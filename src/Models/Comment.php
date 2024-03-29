<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $guarded = ['id'];

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

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function pay(): BelongsTo
    {
        return $this->belongsTo(PayLog::class, 'pay_log_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeReplies(): HasMany
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(CommentScore::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CommentImage::class);
    }

    /**
     * 判斷是否有修改權
     * @param ?Authenticatable $user
     * @return bool
     */
    public function isOwns(?Authenticatable $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        if ($user instanceof StoreUser) {
            return $this->store_id == $user->store_id;
        }

        if ($user instanceof Member) {
            return $this->member_id == $user->id;
        }

        if ($user instanceof AdminUser) {
            return true;
        }

        return false;
    }
}
