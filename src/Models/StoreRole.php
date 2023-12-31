<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreRole extends Model
{
    use HasFactory;

    protected $table = "store_roles";

    protected $guarded = ['id'];

    // 停用
    public const IS_ACTIVE_DISABLED = 0;
    // 啟用
    public const IS_ACTIVE_ENABLED = 1;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(StoreUser::class, 'role_id', 'id');
    }

    public function userPermissions(): HasMany
    {
        return $this->hasMany(StoreUserPermission::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(StorePermission::class, 'store_user_permissions', 'store_role_id', 'store_permission_id');
    }
}
