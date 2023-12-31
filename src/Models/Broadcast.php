<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    use HasFactory;

    protected $table = 'broadcasts';

    protected $guarded = ['id'];

    public $timestamps = true;

    // 已取消
    public const BROADCAST_STATUS_CANCELLED = 0;
    // 已發送
    public const BROADCAST_STATUS_SENT = 1;
    // 待發送
    public const BROADCAST_STATUS_PENDING = 2;
    // 發送中
    public const BROADCAST_STATUS_SENDING = 3;

    // 草稿
    public const STATUS_DRAFT = 0;
    // 上線
    public const STATUS_ONLINE = 1;

    public function logs(): HasMany
    {
        return $this->hasMany(BroadcastLog::class);
    }
}
