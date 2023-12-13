<?php

namespace Mtsung\JoymapCore\Models;

use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use Spatie\Permission\Models\Role;

class AdminRole extends Role
{
    use SerializeDateTrait;

    protected $table = 'admin_roles';

    protected $guarded = ['id'];
}
