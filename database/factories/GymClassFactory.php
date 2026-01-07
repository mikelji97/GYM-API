<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymClass>
 */
class GymClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $classes = ['Yoga', 'Spinning', 'CrossFit', 'Pilates', 'Zumba', 'Boxing'];

        return [
            'name' => fake()->randomElement($classes),
            'description' => fake()->sentence(),
            'duration' => fake()->randomElement([30, 45, 60, 90]),
            'max_capacity' => fake()->numberBetween(10, 30),
        ];
    }
}
