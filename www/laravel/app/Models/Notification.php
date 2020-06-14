<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'title',
        'text',
        'url',
        'read_at'
    ];
}
