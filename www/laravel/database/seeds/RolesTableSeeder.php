<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Consts\Roles::TABLE as $id => $role){
            $roleExists = \App\Models\Role::whereName($role);
            if(!$roleExists->exists()){
                \App\Models\Role::create([
                    'id' => $id,
                    'name' => $role
                ]);
            }
        }
    }
}
