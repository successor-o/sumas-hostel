<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition(): array
    {
        $faculties = ['Clinical Medicine', 'Basic Medical Sciences', 'Allied Health Sciences', 'Applied Sciences'];
        $levels = ['100 Level', '200 Level', '300 Level', '400 Level', '500 Level'];
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $name = $gender === 'Male' ? $this->faker->firstNameMale().' '.$this->faker->lastName() : $this->faker->firstNameFemale().' '.$this->faker->lastName();
        $year = $this->faker->numberBetween(20, 25);

        return [
            'name' => $name,
            'matric_number' => 'SUMAS/'.$year.'/'.$this->faker->unique()->numberBetween(2000, 9999),
            'email' => Str::slug($name, '.').$this->faker->unique()->numberBetween(1, 9999).'@sumas.edu.ng',
            'phone' => '08'.$this->faker->numberBetween(10000000, 99999999),
            'faculty' => $this->faker->randomElement($faculties),
            'level' => $this->faker->randomElement($levels),
            'gender' => $gender,
            'password' => Hash::make('password'),
        ];
    }

    public function male(): static
    {
        return $this->state(fn () => [
            'gender' => 'Male',
            'name' => $this->faker->firstNameMale().' '.$this->faker->lastName(),
        ]);
    }

    public function female(): static
    {
        return $this->state(fn () => [
            'gender' => 'Female',
            'name' => $this->faker->firstNameFemale().' '.$this->faker->lastName(),
        ]);
    }
}
