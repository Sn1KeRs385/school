<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends CRUDController
{
    public $modelClass = News::class;

    public function hook_before_index(&$query)
    {
        $query->orderBy('created_at', 'desc')
            ->with(['creator']);
    }

    public function hook_before_store(Request &$request)
    {
        $user = Auth::user();
        $request['creator_id'] = $user->id;
    }
}