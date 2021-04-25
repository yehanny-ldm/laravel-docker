<?php

use App\Post;
use Illuminate\Support\Str;
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

$factory->define(Post::class, function (Faker $faker) {
    return [
//        'image' => $faker->image(storage_path('images'),640,380,null,false),
        'image' => 'fejk.jpg',
        'title' => $faker->title,
        'content' => $faker->realText(),
        'user_id' => 1, // password
        'category_id' => $faker->numberBetween(1,10),
    ];
});
