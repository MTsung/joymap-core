<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreReplie extends Model
{
    use HasFactory;

    protected $table = 'store_replies';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function storeUser(): BelongsTo
    {
        return $this->belongsTo(StoreUser::class);
    }
}
