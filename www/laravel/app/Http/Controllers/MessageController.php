<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends CRUDController
{
    public $modelClass = Message::class;

    public function unreadCounter(){
        $counter = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return getJson($counter);
    }

    public function getChats(){
        $userId = Auth::id();
        $users = User::query()
            ->select('users.id', 'users.last_name', 'users.first_name', 'users.patronymic')
            ->selectRaw('MAX(messages.created_at) as created_at')
            ->selectRaw("COUNT(IF(messages.read_at IS NULL AND messages.recipient_id = $userId, 1, NULL)) as unread_counter")
            ->leftJoin('messages', function($query){
               $query->on('messages.sender_id', '=', 'users.id')
                   ->orOn('messages.recipient_id', '=', 'users.id');
            })
            ->where(function($query) use($userId){
                $query->where('messages.sender_id', $userId)
                    ->orWhere('messages.recipient_id', $userId);
            })
            ->where('users.id', '<>', $userId)
            ->orderBy('created_at', 'desc')
            ->groupBy('users.id', 'users.last_name', 'users.first_name', 'users.patronymic')
            ->get();
        return getJson($users);
    }

    public function getMessages(Request $request){
        $userId = Auth::id();
        $componentId = $request->input('user_id', null);
        $messages = Message::query()
            ->where(function($query) use($userId, $componentId){
                $query->where(function($query) use($userId, $componentId){
                        $query->where('recipient_id', $userId)
                            ->Where('sender_id', $componentId);
                    })
                    ->orWhere(function($query) use($userId, $componentId){
                        $query->where('recipient_id', $componentId)
                            ->Where('sender_id', $userId);
                    });
            })
            ->with(['sender', 'recipient'])
            ->orderBy('created_at')
            ->get();
        if(count($messages) > 0){
            $created_at = $messages[count($messages) - 1]->created_at;
            Message::where('sender_id', $componentId)
                ->whereNull('read_at')
                ->where('created_at', '<=', $created_at)
                ->update([
                    'read_at' => now()
                ]);
        }
        return getJson($messages);
    }

    public function sendMessage(Request $request){
        $data = [
            'recipient_id' => $request->input('user_id', null),
            'text' => $request->input('message', null),
            'sender_id' => Auth::id()
        ];
        $data['text'] = str_replace("\n", "<br>", $data['text']);
        Message::create($data);
        getJson();
    }
}