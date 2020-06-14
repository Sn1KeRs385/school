<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Class_ extends Model
{
    public function specialization(){
        return $this->belongsTo(Specialization::class);
    }

    protected $table = 'classes';
    public $fillable = [
        'specialization_id',
        'education_begin_at',
        'education_end_at'
    ];
}
