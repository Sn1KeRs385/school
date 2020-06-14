<?php

namespace App\Http\Controllers;

use App\Consts\Roles;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function hook_before_store(Request &$request)
    {
        if($request['password']){
            $request['password'] = Hash::make($request['password']);
        }
    }

    public function hook_before_update(Request &$request, $id)
    {
        if(!$request['password']){
            $request['password'] = Auth::user()->password;
        } else {
            $request['password'] = Hash::make($request['password']);
        }
    }
}
