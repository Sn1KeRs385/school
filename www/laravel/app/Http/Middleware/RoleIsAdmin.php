<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Helpers\ROLES;

class RoleIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user->role_id != ROLES::IS_ADMIN) {
            return new Response(getJson(null, [
                [
                    'code' => 403,
                    'message' => 'MUST_BE_ADMIN_EXCEPTION',
                    'description' => trans('locale.errors.must_be_verified'),
                ],
            ]), 200);
        }
        
        return $next($request);
    }
}
