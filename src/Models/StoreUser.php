<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class StoreUser extends User implements JWTSubject
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $table = 'store_users';

    public $timestamps = true;

    protected $guarded = ['id'];

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
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            // 因為要相容舊的 JWT 所以給這個值，這個值底層是把 Auth Model Hash 起來判斷用的
            'prv' => '2feb025d33cc450eaba533bb46d90be4660aa413',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(StoreRole::class);
    }

    public function storeReplies(): HasMany
    {
        return $this->hasMany(StoreReplie::class);
    }

    public function passwordValidates(): HasMany
    {
        return $this->hasMany(StoreUserPasswordValidate::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(StorePermission::class, 'store_user_permissions', 'store_role_id', 'store_permission_id', 'role_id', 'id');
    }

    public function storeUserPushes(): HasMany
    {
        return $this->hasMany(StoreUserPush::class);
    }

    /**
     * 判斷是否有修改權
     * @param ?Authenticatable $user
     * @return bool
     */
    public function isOwns(?Authenticatable $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        if ($user instanceof StoreUser) {
            return $this->store_id == $user->store_id;
        }

        return false;
    }
}
