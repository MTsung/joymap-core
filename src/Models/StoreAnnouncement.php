<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'store_announcements';

    public $timestamps = true;

    protected $guarded = ['id'];

    // 所有店家
    public const SEND_TYPE_ALL = 0;
    // 標籤店家
    public const SEND_TYPE_TAG = 1;
    // 店家類型
    public const SEND_TYPE_FOOD_TYPE = 2;
    // 指定店家
    public const SEND_TYPE_ASSIGN = 3;

    public function logs()
    {
        return $this->hasMany(StoreAnnouncementLog::class, 'announcement_id');
    }

    public function storeAnnouncementTarget()
    {
        return $this->hasMany(StoreAnnouncementTarget::class, 'store_announcement_id');
    }
}
