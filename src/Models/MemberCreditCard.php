<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberCreditCard extends Model
{
    use HasFactory;

    protected $table = 'member_credit_card';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
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
            return true;
        }

        if ($user instanceof Member) {
            return $this->member_id == $user->id;
        }

        if ($user instanceof AdminUser) {
            return true;
        }

        return false;
    }
}
