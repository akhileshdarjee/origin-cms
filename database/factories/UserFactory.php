<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    $roles = ['Administrator', 'Guest']; // dummy roles
    $is_active = ['1', '0'];

    return [
        'full_name' => $faker->name,
        'login_id' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'role' => $roles[array_rand($roles)],
        'language' => 'en',
        'is_active' => $is_active[array_rand($is_active)],
        'owner' => 'admin',
        'last_updated_by' => 'admin',
    ];
});
