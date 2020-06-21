<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ClassLesson extends Model
{
    public function classSemester(){
        return $this->belongsTo(ClassSemester::class);
    }

    public function studentProgresses(){
        return $this->hasMany(StudentProgress::class);
    }

    public function studentProgress(){
        return $this->hasOne(StudentProgress::class);
    }

    public function reportProgresses($students = null){
        if(!$students) {
            $students = $this->classSemester
                ->cLass
                ->students;
        }
        $progresses = [];
        foreach($students as $student){
            $progress = StudentProgress::where('user_id', $student->id)
                ->where('class_lesson_id', $this->id)
                ->with(['student'])
                ->first();
            if(!$progress){
                $progress = [
                    'user_id' => $student->id,
                    'class_lesson_id' => $this->id,
                    'evaluation' => null,
                    'comment' => null,
                    'user' => $student
                ];
            }
            $progresses[] = $progress;
        }
        return $progresses;
    }

    public $fillable = [
        'class_semester_id',
        'homework',
        'lesson_begin_at',
    ];
}
