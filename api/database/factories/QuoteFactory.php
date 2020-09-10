<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Part;
use App\Quote;
use Faker\Generator as Faker;

$factory->define(Quote::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'more_info' => $faker->paragraph,
        'part_id' => factory(Part::class)->create()
    ];
});
