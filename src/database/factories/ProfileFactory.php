<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\Account\Models\Entities\Profile;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id'      => 1,
        'language'     => $faker->randomElement(config('wk-core.class.core.language')::getCodes()),
        'timezone'     => $faker->randomElement(config('wk-core.class.core.timeZone')::getValues()),
        'gender'       => $faker->randomElement(['female', 'male']),
        'notice_login' => $faker->boolean
    ];
});
