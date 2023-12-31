<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreLightbox extends Model
{
    use HasFactory;

    protected $table = "store_lightboxs";

    protected $guarded = ['id'];

    public function storeLightboxImages(): HasMany
    {
        return $this->hasMany(StoreLightboxImage::class);
    }
}
