<?php

namespace App\Http\Middleware;

use App\AccessToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;

class ApiAuth
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
        // Получаем токен из заголовка Authorization
        $token = $request->header('Authorization');

        // Если токена нет, просим авторизоваться
        if (!$token) {
            return new Response(getJson(null, [
                [
                    'code' => 400,
                    'message' => 'HAVENT_AUTH_HEADER_EXCEPTION',
                    'description' => trans('locale.errors.havent_auth_header'),
                ],
            ]), 200);
        }

        // Ищем токен в базе
        $access_token = AccessToken::where('access_token', $token)->first();

        // Если токена нет, сообщаем об этом
        if (!$access_token) {
            return new Response(getJson(null, [
                [
                    'code' => 401,
                    'message' => 'BAD_TOKEN_EXCEPTION',
                    'description' => trans('locale.errors.bad_token'),
                ],
            ]), 200);
        }

        if ($access_token->expired_at <= Carbon::now() 
            && !$request->is('api/v1/admin/refresh')
            && !$request->is('api/v1/admin/signout')
        ) {
            return new Response(getJson(null, [
                [
                    'code' => 401,
                    'message' => 'TOKEN_EXPIRED_EXCEPTION',
                    'description' => trans('locale.errors.token_expired'),
                ],
            ]), 200);
        }

        // Сохраним эти параметры для передачи дальше
        Auth::setUser($access_token->user); 
        $request['user'] = $access_token->user;
        $request['access_token'] = $access_token;
        return $next($request);
    }
}
