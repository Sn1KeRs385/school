<?php

namespace App\Http\Controllers;

use App\Exceptions\Api\WrongCredentialsException;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignController extends Controller
{
    public function signinByLogin(Request $request)
    {
        $user = User::whereLogin($request->login)
            ->first();

        if (!$user) {
            throw new WrongCredentialsException;
        }
        if (!Auth::attempt($request->only('login', 'password'))) {
            throw new WrongCredentialsException();
        }

        $accessToken = $user->createToken('token');

        return getJson($accessToken);
    }

    public function signout()
    {
        Auth::user()->token()
            ->delete();

        return getJson(null);
    }
}