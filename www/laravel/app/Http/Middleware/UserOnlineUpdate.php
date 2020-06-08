<?php

namespace App\Http\Middleware;

use App\AccessToken;
use Carbon\Carbon;
use Closure;
use Auth;
use Illuminate\Http\Response;

class UserOnlineUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $user->update(['was_online' => Carbon::now()]);
        return $next($request);
    }
}
