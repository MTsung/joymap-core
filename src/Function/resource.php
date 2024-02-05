<?php


use Illuminate\Support\Str;


if (!function_exists('maskName')) {
    function maskName($name): string
    {
        if (Str::length($name) <= 2) {
            return Str::mask($name, 'Ｏ', 1);
        }

        return Str::mask($name, 'Ｏ', 1, -1);
    }
}
