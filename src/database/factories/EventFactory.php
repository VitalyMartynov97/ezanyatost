<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $date = new DateTime('2020-'.$faker->date($format = 'm-d'));
    return [
        'name' => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
        'content' => $faker->text($maxNbChars = 2000),
        'date' => $date,
    ];
});
