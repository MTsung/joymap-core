<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminResource extends Model
{
    protected $table = 'admin_resources';

    protected $guarded = ['id'];

    public function permissions(): HasMany
    {
        return $this->hasMany(AdminPermission::class, 'resource_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AdminResource::class, 'parent_id');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AdminResource::class, 'parent_id');
    }

    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }
}
