<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLightboxImage extends Model
{
    use HasFactory;

    protected $table = "store_lightbox_images";

    protected $guarded = ['id'];

}
