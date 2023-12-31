<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorePermission extends Model
{
    use HasFactory;

    protected $table = 'store_permissions';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function userPermissions(): HasMany
    {
        return $this->hasMany(StoreUserPermission::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(StoreRole::class, 'store_user_permissions', 'store_permission_id', 'store_role_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(StorePermission::class, 'parent_id');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StorePermission::class, 'parent_id');
    }

    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }

}
