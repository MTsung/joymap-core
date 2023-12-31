<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminPermission extends Model
{
    protected $table = 'admin_permissions';

    protected $guarded = ['id'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(AdminResource::class, 'id', 'resource_id');
    }

    /**
     * 取得所有擁有這個權限的角色。
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_has_admin_permissions', 'permission_id', 'role_id');
    }
}
