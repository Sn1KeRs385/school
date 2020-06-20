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

    public $fillable = [
        'class_semester_id',
        'homework',
        'lesson_begin_at',
    ];
}
