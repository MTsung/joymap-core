<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreUserPasswordValidate extends Model
{
    use HasFactory;

    protected $table = 'store_user_password_validates';

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(StoreUser::class);
    }
}
