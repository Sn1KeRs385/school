<?php

namespace App\Http\Controllers;

use App\Models\ClassSemester;
use App\Models\ScheduleType;

class ClassSemesterController extends CRUDController
{
    public $modelClass = ClassSemester::class;

    public function hook_before_all(&$query)
    {
        if(request('class_id')){
            $query->where('class_id', request('class_id'));
        }
        $query->with(['scheduleType'])
            ->orderBy('semester_begin_at' );
    }
}