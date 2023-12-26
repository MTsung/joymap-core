<?php

namespace Mtsung\JoymapCore\Http;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use StdClass;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $needLog = $request->isMethod('post') || $request->isMethod('put');

        if ($needLog) {
            $user = Auth::check() ? Auth::user() : null;

            Log::channel('request')->info('Request: ', [
                'user_type' => get_class($user ?? new StdClass()),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'data' => $request->all(),
                'headers' => Collection::make($request->header())
                    ->filter(fn($v, $k) => $k != 'cookie')
                    ->toArray(),
            ]);
        }

        $response = $next($request);

        if ($needLog) {
            $resContent = $response->getContent();

            Log::channel('request')->info('Response: ', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'response' => Str::isJson($resContent) ? json_decode($resContent) : $resContent,
            ]);
        }

        return $response;
    }
}
