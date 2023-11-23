<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreRole extends Model
{
    use HasFactory;

    protected $table = "store_roles";

    protected $guarded = [];

    // 停用
    public const IS_ACTIVE_DISABLED = 0;
    // 啟用
    public const IS_ACTIVE_ENABLED = 1;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function users()
    {
        return $this->hasMany(StoreUser::class, 'role_id', 'id');
    }

    public function userPermissions()
    {
        return $this->hasMany(StoreUserPermission::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(StorePermission::class, 'store_user_permissions', 'store_role_id', 'store_permission_id');
    }
}
