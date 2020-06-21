<?php

namespace App\Http\Controllers;

use App\Consts\Roles;
use App\Models\ClassLesson;
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
        $user->roles;

        return getJson($user);
    }

    public function hook_before_index(&$query)
    {
        $user = Auth::user();
        $isTeacher = $user->roles()
            ->whereIn('role_id', [Roles::TEACHER])
            ->exists();
        if($isTeacher){
            $classId = \Illuminate\Support\Facades\DB::table('class_members')
                ->where('user_id', $user->id)
                ->where('role_id', Roles::TEACHER)
                ->pluck('class_id');
            $usersId = \Illuminate\Support\Facades\DB::table('class_members')
                ->whereIn('class_id', $classId)
                ->where('role_id', Roles::STUDENT)
                ->pluck('user_id');
            $query->whereIn('id', $usersId);
        }
        $isParent = $user->roles()
            ->whereIn('role_id', [Roles::PARENT])
            ->exists();
        if($isParent){
            $usersId = \Illuminate\Support\Facades\DB::table('user_relations')
                ->where('parent_id', $user->id)
                ->pluck('student_id');
            $query->whereIn('id', $usersId);
        }
    }

    public function hook_before_all(&$query)
    {
        if(request('type') === 'students'){
            $query->whereHas('roles', function($query){
               $query->whereId(Roles::STUDENT);
            });
        } elseif(request('type') === 'teachers'){
            $query->whereHas('roles', function($query){
                $query->whereId(Roles::TEACHER);
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

    public function hook_before_show(&$query, $id)
    {
        $query->with(['roles', 'classesWhereTeacher.specialization', 'classesWhereStudent.specialization', 'relationStudents', 'relationParents']);
    }

    public function getSchedule(Request $request)
    {
        $dateStart = $request->input('start_at') . ' 00:00:00';
        $dateEnd = $request->input('end_at') . ' 23:59:59';
        $userId = $request->input('user_id');

        $lessons = ClassLesson::query()
            ->whereHas('classSemester', function($query) use($userId){
               $query->whereHas('cLass', function($query) use($userId){
                  $query->whereHas('members', function($query) use($userId){
                     $query->where('id', $userId);
                  });
               });
            })
            ->where('lesson_begin_at', '>=', $dateStart)
            ->where('lesson_begin_at', '<=', $dateEnd)
            ->orderBy('lesson_begin_at')
            ->with([
                'classSemester.cLass.specialization',
                'studentProgress' => function($query) use($userId){
                    $query->where('user_id', $userId);
                }
            ])
            ->get();

        return getJson($lessons);
    }
}
