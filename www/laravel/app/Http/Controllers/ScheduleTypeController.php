<?php

namespace App\Http\Controllers;

use App\Models\ScheduleType;

class ScheduleTypeController extends CRUDController
{
    public $modelClass = ScheduleType::class;
}