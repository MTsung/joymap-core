<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLightbox extends Model
{
    use HasFactory;

    protected $table = "store_lightboxs";

    protected $guarded = ['id'];

    public function storeLightboxImages()
    {
        return $this->hasMany(StoreLightboxImage::class);
    }
}
