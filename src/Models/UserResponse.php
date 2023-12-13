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
}
