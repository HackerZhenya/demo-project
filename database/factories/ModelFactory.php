<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Person::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'city' => $faker->city,
        'job' => $faker->jobTitle
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $time = $faker->date() . " " . $faker->time();

    return [
        'person_id' => $faker->numberBetween(1, 100),
        'head' => $faker->sentence,
        'body' => $faker->realText(),
        'published' => $faker->boolean(80),
        'created_at' => $time,
        'updated_at' => $time
    ];
});
