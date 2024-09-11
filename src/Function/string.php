<?php


if (!function_exists('br2nl')) {
    function br2nl($string): string
    {
        return preg_replace('/<br\\s*?\/??>/i', "\r\n", $string);
    }
}
