<?php

namespace Mtsung\JoymapCore\Action;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

trait AsObject
{
    public static function make(): self
    {
        return app(static::class);
    }

    /**
     * @param mixed ...$arguments
     * @return mixed
     * @see static::handle()
     */
    public static function run(...$arguments): mixed
    {
        Log::info(__CLASS__ . '::run($params)', [$arguments]);

        return static::make()->handle(...$arguments);
    }

    /**
     * @param $boolean
     * @param ...$arguments
     * @return mixed|Fluent
     */
    public static function runIf($boolean, ...$arguments): mixed
    {
        return $boolean ? static::run(...$arguments) : new Fluent;
    }

    /**
     * @param $boolean
     * @param ...$arguments
     * @return mixed|Fluent
     */
    public static function runUnless($boolean, ...$arguments): mixed
    {
        return static::runIf(!$boolean, ...$arguments);
    }
}