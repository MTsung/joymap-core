<?php


if (!function_exists('isLocal')) {
    function isLocal(): bool
    {
        return env('APP_ENV') == 'local';
    }
}

if (!function_exists('isTesting')) {
    function isTesting(): bool
    {
        return env('APP_ENV') == 'testing';
    }
}

if (!function_exists('isProd')) {
    function isProd(): bool
    {
        return env('APP_ENV') == 'production';
    }
}