<?php

use App\Helpers\ROLES;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

// Базовая модель БЕЗ указания роли
$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'patronymic' => $faker->firstName(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
