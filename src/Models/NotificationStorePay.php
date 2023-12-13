<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationStorePay extends Model
{
    use HasFactory;

    protected $table = 'notification_store_pay';

    protected $guarded = ['id'];

    public function getMorphClass()
    {
        return $this->getTable();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function notify()
    {
        return $this->morphOne(Notification::class, 'notify');
    }
}
