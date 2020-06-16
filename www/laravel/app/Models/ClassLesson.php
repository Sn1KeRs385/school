<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ClassLesson extends Model
{
    public function classSemester(){
        return $this->belongsTo(ClassSemester::class);
    }

    public $fillable = [
        'class_semester_id',
        'homework',
        'lesson_begin_at',
    ];
}
