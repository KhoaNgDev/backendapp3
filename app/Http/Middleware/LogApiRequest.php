<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        ApiLog::create([
            'user_id' => optional($request->user())->id,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'payload' => json_encode($request->except(['password', 'token'])),
            'status_code' => $response->getStatusCode(),
            'duration' => round((microtime(true) - $start) * 1000),
        ]);

        return $response;
    }
}