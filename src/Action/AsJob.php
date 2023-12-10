<?php

namespace Mtsung\JoymapCore\Action;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;

trait AsJob
{
    public static function makeJob(...$arguments): ShouldQueue
    {
        return new JobDecorator(static::class, ...$arguments);
    }

    public static function dispatch(...$arguments): PendingDispatch
    {
        return dispatch(self::makeJob(...$arguments));
    }

    public static function dispatchDelay(int $seconds, ...$arguments): PendingDispatch
    {
        return dispatch(self::makeJob(...$arguments)->delay($seconds));
    }
}