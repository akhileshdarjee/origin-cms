<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    $roles = ['Administrator', 'Guest']; // dummy roles
    $active = [1, 0];
    $active = $active[array_rand($active)];
    $title = $faker->title;
    $first_name = $faker->firstName;
    $last_name = $faker->lastName;
    $full_name = $title . ' ' . $first_name . ' ' . $last_name;
    $now_time = date('Y-m-d H:i:s');

    return [
        'title' => $title,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'full_name' => $full_name,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $active ? $now_time : null,
        'password' => bcrypt(Str::random(10)),
        'role' => $roles[array_rand($roles)],
        'locale' => 'en',
        'active' => $active,
        'owner' => 'admin',
        'last_updated_by' => 'admin',
        'created_at' => $now_time,
        'updated_at' => $now_time
    ];
});
