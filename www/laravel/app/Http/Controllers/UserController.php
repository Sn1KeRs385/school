<?php

namespace App\Http\Controllers;

use App\ApiModels\Apartment;
use App\ApiModels\Resident;
use App\ApiModels\Settlement;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\RegisterRequest;
use App\Http\Requests\Api\Users\UniqueCheckRequest;
use App\Http\Requests\Api\Users\UpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    public function me()
    {
        $user = Auth::user();


        return getJson($user);
    }
}
