<?php

namespace App\Http\Controllers;

use App\Models\Class_;

class ClassController extends CRUDController
{
    public $modelClass = Class_::class;

    public function hook_before_index(&$query)
    {
        $query->with(['specialization']);
    }
}