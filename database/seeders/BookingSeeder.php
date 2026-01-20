<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $sessions = Session::all()->take(20);

        if ($users->isEmpty()) {
            return;
        }

        foreach ($sessions as $session) {
            // Crear entre 1 y 5 reservas por sesion
            $numBookings = rand(1, min(5, $session->max_capacity));

            for ($i = 0; $i < $numBookings; $i++) {
                $user = $users->random();

                // Verificar que el usuario no tenga ya reserva en esta sesion
                $existingBooking = Booking::where('user_id', $user->id)
                    ->where('session_id', $session->id)
                    ->first();

                if (!$existingBooking) {
                    $statuses = ['confirmed', 'confirmed', 'confirmed', 'attended', 'cancelled'];

                    Booking::create([
                        'user_id' => $user->id,
                        'session_id' => $session->id,
                        'status' => $statuses[array_rand($statuses)],
                    ]);

                    $session->increment('current_bookings');
                }
            }
        }
    }
}
