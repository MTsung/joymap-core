<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreReplie extends Model
{
    use HasFactory;

    protected $table = 'store_replies';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function storeUser()
    {
        return $this->belongsTo(StoreUser::class);
    }
}
