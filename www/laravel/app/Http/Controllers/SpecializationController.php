<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Role;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecializationController extends CRUDController
{
    public $modelClass = Specialization::class;
}