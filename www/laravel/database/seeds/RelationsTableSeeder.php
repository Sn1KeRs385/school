<?php

use Illuminate\Database\Seeder;

class RelationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Consts\Relations::TABLE as $id => $relation){
            $relationExists = \App\Models\Relation::whereName($relation);
            if(!$relationExists->exists()){
                \App\Models\Relation::create([
                    'id' => $id,
                    'name' => $relation
                ]);
            }
        }
    }
}
