<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ClassSemester extends Model
{
    public function scheduleType(){
        return $this->belongsTo(ScheduleType::class);
    }

    public function classLessons(){
        return $this->hasMany(ClassLesson::class);
    }

    public function cLass(){
        return $this->belongsTo(Class_::class, 'class_id');
    }

    public $fillable = [
        'class_id',
        'schedule_type_id',
        'semester_begin_at',
        'semester_end_at',
        'lesson_time'
    ];
}
