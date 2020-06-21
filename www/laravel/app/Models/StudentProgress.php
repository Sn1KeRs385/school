<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    public function student(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lesson(){
        return $this->belongsTo(ClassLesson::class, 'class_lesson_id');
    }

    protected $table = 'student_progresses';
    
    public $fillable = [
        'user_id',
        'class_lesson_id',
        'evaluation',
        'comment',
    ];
}
