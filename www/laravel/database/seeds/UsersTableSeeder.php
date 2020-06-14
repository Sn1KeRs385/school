<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('users')->count() != 0) {
            return;
        }
        \App\User::create([
            'last_name' => 'Кузнецова',
            'first_name' => 'Ангелина',
            'patronymic' => 'Евгеньевна',
            'login' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('230584'),
        ]);
    }
}
