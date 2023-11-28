<?php

namespace Mtsung\JoymapCore\Facades\Media;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string compress($image)
 *
 * @see \Mtsung\JoymapCore\Helpers\Media\Image
 */
class Image extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Media\Image::class;
    }
}
