<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreSpgateway extends Model
{
    use HasFactory;

    protected $table = 'store_spgateway';

    protected $guarded = ['id'];

    protected $casts = [
        'post_data' => 'array',
        'response_data' => 'array',
        'callback_data' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
