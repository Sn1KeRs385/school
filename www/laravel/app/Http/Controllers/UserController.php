<?php

namespace App\Http\Controllers;

use App\ApiModels\Apartment;
use App\ApiModels\Resident;
use App\ApiModels\Settlement;
use App\Consts\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\RegisterRequest;
use App\Http\Requests\Api\Users\UniqueCheckRequest;
use App\Http\Requests\Api\Users\UpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class UserController extends CRUDController
{
    public $modelClass = User::class;
    public function me()
    {
        $user = Auth::user();


        return getJson($user);
    }

    public function hook_before_all(&$query)
    {
        if(request('type') === 'students'){
            $query->whereHas('roles', function($query){
               $query->whereId(Roles::STUDENT);
            });
        }
    }

    public function hook_after_store(&$model)
    {
        if(request('roles')){
            $roleIds = [];
            foreach(request('roles') as $role){
                $roleIds[] = $role['id'];
            }
            $model->roles()
                ->sync($roleIds);
        }
        
        if(request('specializations')){
            $specializationIds = [];
            foreach(request('specializations') as $specialization){
                $specializationIds[] = $specialization['id'];
            }
            $model->specializations()
                ->sync($specializationIds);
        }

        if(request('relations')){
            foreach(request('relations') as $relation){
                $model->relationStudents()
                    ->attach($relation['student_id'], ['relation_id' => $relation['relation_id']]);
            }
        }
    }
}
