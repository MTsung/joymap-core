<?php


if (!function_exists('isLocal')) {
    function isLocal(): bool
    {
        checkAppEnv();
        return env('APP_ENV') == 'local';
    }
}

if (!function_exists('isTesting')) {
    function isTesting(): bool
    {
        checkAppEnv();
        return env('APP_ENV') == 'testing';
    }
}

if (!function_exists('isProd')) {
    function isProd(): bool
    {
        checkAppEnv();
        return env('APP_ENV') == 'production';
    }
}

if (!function_exists('checkAppEnv')) {
    /**
     * @throws Exception
     */
    function checkAppEnv(): void
    {
        if (!in_array(env('APP_ENV'), ['local', 'testing', 'production'])) {
            throw new Exception('APP_ENV 必須為 local、testing、production 其中一項');
        }
    }
}