<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

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
            // 'hospital_id' => rand(1, 10),
            'hospital_id' => Hospital::factory(),
            'address' => $this->faker->address,
            'image' => $this->faker->imageUrl(640, 480, 'doctor', true),
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'specialty'   => '1',
        ];
    }
}
