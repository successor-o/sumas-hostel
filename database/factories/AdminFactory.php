<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    protected $model = \App\Models\Admin::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '08'.$this->faker->numberBetween(10000000, 99999999),
            'staff_id' => 'SUMAS-ST-'.$this->faker->unique()->numberBetween(1000, 9999),
            'role' => 'System Administrator',
            'password' => Hash::make('password'),
        ];
    }
}
