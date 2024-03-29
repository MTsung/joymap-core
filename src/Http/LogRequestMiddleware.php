<?php

namespace Mtsung\JoymapCore\Http;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use StdClass;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $needLog = (
                $request->isMethod('post') ||
                $request->isMethod('put')
            ) && empty($request->page) && empty($request->meter);
        $uuid = Str::uuid();
        Config::set('__tracking_code__', $uuid);

        if ($needLog) {
            $user = Auth::check() ? Auth::user() : null;

            Log::channel('request')->info($uuid . '-Request: ', [
                'user_type' => get_class($user ?? new StdClass()),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'data' => Collection::make($request->all())
                    ->filter(fn($v, $k) => !Str::contains($k, ['password', 'card_no']))
                    ->toArray(),
                'headers' => Collection::make($request->header())
                    ->filter(fn($v, $k) => $k != 'cookie')
                    ->toArray(),
            ]);
        }

        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);
        if (($end - $start) > env('SLOW_REQUEST_SECOND', 1)) {
            Log::channel('slow_request')->info(
                $uuid . ' ' . $request->method() . ' ' . $request->fullUrl(),
                [$end - $start]
            );
        }

        if ($needLog) {
            $resContent = $response->getContent();

            Log::channel('request')->info($uuid . '-Response: ', [
                'run-time' => $end - $start,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'response' => Str::isJson($resContent) ? json_decode($resContent) : $resContent,
            ]);
        }

        return $response;
    }
}
