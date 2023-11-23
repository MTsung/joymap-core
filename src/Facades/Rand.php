<?php

namespace Mtsung\JoymapCore\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static string numberString(int $length = 8,string $prefix = '')
 * @method static string englishNumberString(int $length = 6,string $prefix = '')
 *
 * @see \Mtsung\JoymapCore\Helpers\Upload\UploadGcs
 */
class Rand extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Rand::class;
    }
}
