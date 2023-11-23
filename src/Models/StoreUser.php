<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class StoreUser extends User implements JWTSubject
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $table = 'store_users';

    public $timestamps = true;

    protected $guarded = [];

    protected string $guard_name = 'store';

    // 停用
    public const IS_ACTIVE_DISABLED = 0;
    // 啟用
    public const IS_ACTIVE_ENABLED = 1;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function role()
    {
        return $this->belongsTo(StoreRole::class);
    }

    public function storeReplies()
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function passwordValidates()
    {
        return $this->hasMany(StoreUserPasswordValidate::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(StorePermission::class, 'store_user_permissions', 'store_role_id', 'store_permission_id', 'role_id', 'id');
    }

    public function storeUserPushes()
    {
        return $this->hasMany(StoreUserPush::class);

    }
}
