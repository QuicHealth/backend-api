<?php

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HospitalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hospital::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'unique_id' => uniqid(),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'image' => $this->faker->imageUrl(640, 480, 'hospital', true),
            'latitude' => $this->faker->latitude($min = -90, $max = 90),
            'longitude' => $this->faker->longitude($min = -180, $max = 180),
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'state'   => $this->faker->state,
            'country' => $this->faker->country,
            'description' => $this->faker->text,
        ];
    }
}