<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreUserPermission extends Model
{
    use HasFactory;

    protected $table = 'store_user_permissions';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['id'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(StoreRole::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(StorePermission::class);
    }
}
