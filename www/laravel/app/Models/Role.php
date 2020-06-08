<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public $fillable = [
        'name'
    ];


}
