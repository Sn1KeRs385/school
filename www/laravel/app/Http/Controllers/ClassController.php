<?php

namespace App\Http\Controllers;

use App\Consts\Roles;
use App\Models\Class_;
use App\Models\ClassLesson;
use App\Models\StudentProgress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getStudentsWithProgress(Request $request)
    {
        $lesson = ClassLesson::find($request->input('lesson_id', null));
        if(!$lesson){
            return getJson();
        }
        $students = $lesson->classSemester
            ->cLass
            ->students;
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