<?php

namespace App\Models;


use App\Consts\Roles;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Class_ extends Model
{
    public function specialization(){
        return $this->belongsTo(Specialization::class);
    }

    public function teachers(){
        return $this->members()
            ->wherePivot('role_id', Roles::TEACHER);
    }

    public function students(){
        return $this->members()
            ->wherePivot('role_id', Roles::STUDENT);
    }

    public function members(){
        return $this->belongsToMany(User::class, 'class_members', 'class_id')
            ->withPivot('role_id');
    }

    public function semesters(){
        return $this->hasMany(ClassSemester::class);
    }

    protected $table = 'classes';
    public $fillable = [
        'specialization_id',
        'name',
        'education_begin_at',
        'education_end_at'
    ];
}
