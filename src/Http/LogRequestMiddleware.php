<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('post') || $request->isMethod('put')) {
            Log::channel('request')->info('Request:', [
                'user_id' => Auth::check() ? Auth::user()->id : 0,
                'user_name' => Auth::check() ? Auth::user()->name : '',
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'data' => $request->all(),
                'headers' => Collection::make($request->header())
                    ->filter(fn($v, $k) => $k != 'cookie')
                    ->toArray(),
            ]);
        }

        return $next($request);
    }
}
