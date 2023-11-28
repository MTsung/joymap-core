<?php

namespace Mtsung\JoymapCore\Facades\Upload;

use Illuminate\Support\Facades\Facade;


/**
 * @method static string putImage($image, string $type)
 * @method static bool delete($url)
 *
 * @see \Mtsung\JoymapCore\Helpers\Upload\UploadGcs
 */
class UploadGcs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Upload\UploadGcs::class;
    }
}
