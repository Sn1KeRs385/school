<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(){
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public $fillable = [
        'sender_id',
        'recipient_id',
        'text',
        'read_at'
    ];
}
