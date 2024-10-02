<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FirmCooperation extends Model
{
    use HasFactory;

    protected $table = 'firm_cooperation';

    protected $guarded = ['id'];

    // 未處理
    public const PROCESSING_STATUS_PENDING = 0;
    // 進行中
    public const PROCESSING_STATUS_IN_PROGRESS = 1;
    // 已結案
    public const PROCESSING_STATUS_CLOSED = 2;
}
