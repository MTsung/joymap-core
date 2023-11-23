<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreUserPermission extends Model
{
    use HasFactory;

    protected $table = 'store_user_permissions';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(StoreRole::class);
    }

    public function permission()
    {
        return $this->belongsTo(StorePermission::class);
    }
}
