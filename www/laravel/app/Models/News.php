<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = "news";

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }

    public $fillable = [
        'title',
        'text',
        'creator_id'
    ];
}
