<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property array permission_name_list
 */
class AdminUser extends User
{
    use HasFactory, HasRoles, SerializeDateTrait;

    protected $table = "admin_users";

    protected $guarded = ['id'];

    public function getPermissionNameListAttribute(): array
    {
        return $this->getPermissionsViaRoles()->pluck('name')->toArray();
    }

    public function storeGroup(): BelongsTo
    {
        return $this->belongsTo(StoreGroup::class);
    }
}
