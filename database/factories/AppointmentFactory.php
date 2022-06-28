<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Appointment;
use Faker\Generator as Faker;

$factory->define(Appointment::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'doctor_id' => 1,
        'day_id' => 1,
        'from' => "1pm",
        'to' => "2pm",
        'status' => 'pending',
    ];
});