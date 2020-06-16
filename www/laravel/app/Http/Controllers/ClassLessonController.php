<?php

namespace App\Http\Controllers;

use App\Models\ClassLesson;
use App\Models\ClassSemester;
use Carbon\Carbon;

class ClassLessonController extends CRUDController
{
    public $modelClass = ClassLesson::class;

    public function hook_before_all(&$query)
    {
        if(request('semester_id')){
            $query->where('class_semester_id', request('semester_id'));
        }
        $query->orderBy('lesson_begin_at', 'desc');
    }

    public function hook_after_store(&$model)
    {
        if(request('auto_fill')){
            $dateEnd = $model->classSemester->semester_end_at;
            if($dateEnd){
                $dateEnd = (new Carbon($dateEnd))
                    ->setHour(23)
                    ->setMinutes(59)
                    ->setSeconds(59);
                $date = (new Carbon(request('lesson_begin_at')))->addDays(7);
                $insertArray = [];
                while($date < $dateEnd){
                    $insertArray[] =[
                        'lesson_begin_at' => $date->format('Y-m-d H:i:s'),
                    ];
                    $date = $date->addDays(7);
                }
                $model->classSemester
                    ->classLessons()
                    ->createMany($insertArray);
            }
        }
    }
}