<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends User
{
    use HasFactory, HasRoles, SerializeDateTrait;

    protected $table = "admin_users";

    protected $guarded = [];

    public function getPermissionNameListAttribute()
    {
        return $this->getPermissionsViaRoles()->pluck('name')->toArray();
    }

}
