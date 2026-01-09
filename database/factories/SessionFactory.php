<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gym_class_id' => \App\Models\GymClass::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => fake()->randomElement(['Sala 1', 'Sala 2', 'Sala 3']),
            'max_capacity' => fake()->numberBetween(10, 30),
            'current_bookings' => 0,
        ];
    }
}
