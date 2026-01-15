<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreGroup extends Model
{
    use HasFactory;

    protected $table = 'store_group';

    protected $guarded = ['id'];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function adminUsers(): HasMany
    {
        return $this->hasMany(AdminUser::class);
    }
}
