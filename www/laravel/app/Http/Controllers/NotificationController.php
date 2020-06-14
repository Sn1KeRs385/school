<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends CRUDController
{
    public $modelClass = Notification::class;

    public function hook_before_all(&$query)
    {
        $query->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
    }

    public function readAll(Request $request)
    {
        $created_at = $request->input('created_at', null);
        Notification::where('user_id', Auth::id())
            ->where('created_at', '<=', $created_at)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
        return getJson();
    }
}