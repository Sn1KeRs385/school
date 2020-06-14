<?php

use Illuminate\Database\Seeder;

class ScheduleTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Consts\ScheduleTypes::TABLE as $id => $scheduleType){
            $scheduleTypeExists = \App\Models\ScheduleType::whereName($scheduleType);
            if(!$scheduleTypeExists->exists()){
                \App\Models\ScheduleType::create([
                    'id' => $id,
                    'name' => $scheduleType
                ]);
            }
        }
    }
}
