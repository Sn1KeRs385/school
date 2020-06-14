<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends CRUDController
{
    public $modelClass = Role::class;
}