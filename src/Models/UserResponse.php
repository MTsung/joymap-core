<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserResponse extends Model
{
    use HasFactory;

    protected $table = 'user_response';

    protected $guarded = ['id'];

    public $timestamps = true;

    // 一般建議回報
    public const STATUS_GENERAL_REPORT = 0;
    // 店家服務回報
    public const STATUS_SERVICE_REPORT = 1;
    // 系統異常
    public const STATUS_SYSTEM_ABNORMALITY = 2;

    // 未處理
    public const PROCESSING_STATUS_PENDING = 0;
    // 進行中
    public const PROCESSING_STATUS_IN_PROGRESS = 1;
    // 已結案
    public const PROCESSING_STATUS_CLOSED = 2;
}
