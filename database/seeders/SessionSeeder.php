<?php

namespace Database\Seeders;

use App\Models\Session;
use App\Models\GymClass;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = GymClass::all();
        $rooms = ['Sala 1', 'Sala 2', 'Sala 3', 'Sala Principal'];
        $startDate = Carbon::today();

        foreach ($classes as $class) {
            // Crear sesiones para los proximos 7 dias
            for ($day = 0; $day < 7; $day++) {
                $date = $startDate->copy()->addDays($day);

                // Sesion de manana
                Session::create([
                    'gym_class_id' => $class->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => '09:00',
                    'end_time' => '10:00',
                    'room' => $rooms[array_rand($rooms)],
                    'max_capacity' => $class->max_capacity,
                    'current_bookings' => 0,
                ]);

                // Sesion de tarde
                Session::create([
                    'gym_class_id' => $class->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => '18:00',
                    'end_time' => '19:00',
                    'room' => $rooms[array_rand($rooms)],
                    'max_capacity' => $class->max_capacity,
                    'current_bookings' => 0,
                ]);
            }
        }
    }
}
