<?php

namespace Mtsung\JoymapCore\Action;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;
use Throwable;

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
        $logData = [];
        try {
            // 怕太多 Log 只抓第一個值
            $logData = collect($arguments)->map(fn($v) => [
                'type' => gettype($v),
                'data' => is_scalar($v) ? $v : collect($v)->take(1)->toArray(),
            ])->toArray();
        } catch (Throwable $e) {
            Log::error(__CLASS__, [$e]);
        }
        Log::info(__CLASS__ . '::run($params)', $logData);

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