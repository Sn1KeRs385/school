<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progresses';
    
    public $fillable = [
        'user_id',
        'class_lesson_id',
        'evaluation',
        'comment',
    ];
}
