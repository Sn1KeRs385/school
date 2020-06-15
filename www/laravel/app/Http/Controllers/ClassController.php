<?php

namespace App\Http\Controllers;

use App\Consts\Roles;
use App\Models\Class_;
use Illuminate\Http\Request;

class ClassController extends CRUDController
{
    public $modelClass = Class_::class;

    public function hook_before_index(&$query)
    {
        $query->with(['specialization']);
    }

    public function hook_before_show(&$query, $id)
    {
        $query->with(['specialization'])
            ->withCount(['teachers', 'students']);
    }

    public function getMembers(Request $request)
    {
        $classId = $request->input('class_id', null);
        $class = Class_::find($classId);
        if(!$class){
            return getJson();
        }
        if($request->input('type', null) === 'students'){
            $members = $class->students;
        } elseif($request->input('type', null) === 'teachers'){
            $members = $class->teachers;
        } else {
            $members = $class->members;
        }
        return getJson($members);
    }

    public function setMembers(Request $request)
    {
        $classId = $request->input('class_id', null);
        $class = Class_::find($classId);
        if(!$class){
            return getJson();
        }
        $userIds = [];
        foreach($request->input('users') as $user){
            $userIds[] = $user['value'];
        }
        if($request->input('type', null) === 'students'){
            $class->students()
                ->detach();
            $role_id = Roles::STUDENT;
        } elseif($request->input('type', null) === 'teachers'){
            $class->teachers()
                ->detach();
            $role_id = Roles::TEACHER;
        } else {
            $class->members()
                ->detach();
            $role_id = Roles::STUDENT;
        }

        $members = $class->members()
            ->attach($userIds, ['role_id' => $role_id]);
        return getJson($members);
    }
}