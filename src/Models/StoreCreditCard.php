<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCreditCard extends Model
{
    use HasFactory;

    protected $table = 'store_credit_card';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
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

        if ($user instanceof Member) {
            return false;
        }

        if ($user instanceof AdminUser) {
            return true;
        }

        return false;
    }
}
