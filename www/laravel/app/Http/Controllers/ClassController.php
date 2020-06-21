<?php

namespace App\Http\Controllers;

use App\Consts\Roles;
use App\Models\Class_;
use App\Models\ClassLesson;
use App\Models\StudentProgress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends CRUDController
{
    public $modelClass = Class_::class;

    public function hook_before_index(&$query)
    {
        $query->with(['specialization']);

        $user = Auth::user();
        $isTeacher = $user->roles()
            ->whereIn('role_id', [Roles::TEACHER])
            ->exists();
        if($isTeacher){
            $classId = \Illuminate\Support\Facades\DB::table('class_members')
                ->where('user_id', $user->id)
                ->where('role_id', Roles::TEACHER)
                ->pluck('class_id');
            $query->whereIn('id', $classId);
        }
        $isParent = $user->roles()
            ->whereIn('role_id', [Roles::PARENT])
            ->exists();
        if($isParent){
            $usersId = \Illuminate\Support\Facades\DB::table('user_relations')
                ->where('parent_id', $user->id)
                ->pluck('student_id');
            $classId = \Illuminate\Support\Facades\DB::table('class_members')
                ->whereIn('user_id', $usersId)
                ->where('role_id', Roles::STUDENT)
                ->pluck('class_id');
            $query->whereIn('id', $classId);
        }
        $isStudent = $user->roles()
            ->whereIn('role_id', [Roles::STUDENT])
            ->exists();
        if($isStudent){
            $classId = \Illuminate\Support\Facades\DB::table('class_members')
                ->where('user_id', $user->id)
                ->where('role_id', Roles::STUDENT)
                ->pluck('class_id');
            $query->whereIn('id', $classId);
        }
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

    public function getStudentsWithProgress(Request $request)
    {
        $lesson = ClassLesson::find($request->input('lesson_id', null));
        if(!$lesson){
            return getJson();
        }
        $user = Auth::user();
        $students = $lesson->classSemester
            ->cLass
            ->students();
        $isTeacherOrAdmin = $user->roles()
            ->whereIn('role_id', [Roles::TEACHER, Roles::DIRECTOR_ASSISTANT, Roles::DIRECTOR])
            ->exists();
        if(!$isTeacherOrAdmin){
            $usersId = DB::table('user_relations')
                ->where('parent_id', $user->id)
                ->pluck('student_id');
            $usersId[] = $user->id;
            $students->whereIn('id', $usersId);
        }
        $students = $students->get();
        $students->each(function($item) use($lesson){
           $item->progress = $item->progressInLesson($lesson);
        });
        return getJson($students);
    }

    public function saveStudentsProgress(Request $request)
    {
        $data = $request->input('data', []);
        foreach($data as $progress){
            $studentProgress = StudentProgress::firstOrCreate([
                'user_id' => $progress['user_id'],
                'class_lesson_id' => $progress['class_lesson_id'],
            ]);
            $studentProgress->update([
                'evaluation' => $progress['evaluation'],
                'comment' => $progress['comment'] ?? null,
            ]);
            if($progress['notification']){
                $lesson = ClassLesson::find($progress['class_lesson_id']);
                $user = User::find($progress['user_id']);
                $class = $lesson->classSemester
                    ->cLass;
                $date = new \DateTime($lesson->lesson_begin_at);
                $sender = Auth::user();
                $notification = [
                    'title' => "Направление \"{$class->specialization->name}\" от {$date->format('d.m.Y H:i')}",
                    'text' => "{$sender->getFIO()} просит вас обратить внимание на данный урок.<br>Ученик: {$user->getFIO()}",
                ];
                foreach($user->relationParents as $parent){
                    $parent->notifications()
                        ->create($notification);
                }
            } elseif(in_array($progress['evaluation'], [1, 2, "1", "2"])){
                $lesson = ClassLesson::find($progress['class_lesson_id']);
                $user = User::find($progress['user_id']);
                $class = $lesson->classSemester
                    ->cLass;
                $date = new \DateTime($lesson->lesson_begin_at);
                $notification = [
                    'title' => "Направление \"{$class->specialization->name}\" от {$date->format('d.m.Y H:i')}",
                    'text' => "{$user->getFIO()} получил оценку {$progress['evaluation']}",
                ];
                foreach($user->relationParents as $parent){
                    $parent->notifications()
                        ->create($notification);
                }
            }
        }
        return getJson();
    }
}