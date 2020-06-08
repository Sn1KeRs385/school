<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HttpLogger
{
    public function handle($request, Closure $next)
    {
        if (!$this->inExceptArray($request)) {
            $request->start = microtime(true);
            $request->logThisRoute = true;
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ($request->logThisRoute) {
            $request->end = microtime(true);
            $this->log($request, $response);
        }
    }

    protected function log($request, $response)
    {
        $duration = $request->end - $request->start;
        $ip = $request->getClientIp();
        $resp = Str::limit($response->getContent(),512);
        $date = date("Y-m-d H:i:s", $request->start);
        $log = "{$ip}  $date  {$duration}ms \n" .
            "Request : { $request } \n" .
            "Response : { $resp }
        -------------------------------------------------------------------------------- \n";

        file_put_contents(storage_path('logs/request_log_' . date("Y-m-d") . '.log'), $log, FILE_APPEND);
    }
    protected $except = [
        '/api/v1/admin/*',
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');

            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
