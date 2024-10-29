<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DesignatedDriverMatch extends Model
{
    use HasFactory;

    protected $table = 'designated_driver_match';

    public $timestamps = true;

    protected $guarded = ['id'];

    // TWDD
    public const TYPE_TWDD = 0;

    // 媒合失敗
    public const STATUS_MATCH_FAIL = 0;
    // 媒合中
    public const STATUS_MATCHING = 1;
    // 媒合成功
    public const STATUS_MATCH_SUCCESS = 2;
    // 取消
    public const STATUS_CANCEL = 3;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function designatedDriverTwdd(): HasOne
    {
        return $this->hasOne(DesignatedDriverTwdd::class);
    }
}
