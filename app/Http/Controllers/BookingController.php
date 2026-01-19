<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $bookings = Booking::all();
        } else {
            $bookings = Booking::where('user_id', $user->id)->get();
        }

        return response()->json(['data' => $bookings], 200);
    }
    public function myBookings(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)->get();

        return response()->json(['data' => $bookings], 200);
    }
    public function store(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'session_id' => 'required|exists:gym_sessions,id',
    ]);

    $session = \App\Models\Session::findOrFail($validated['session_id']);

    // comprobar sesion esta llena
    if ($session->current_bookings >= $session->max_capacity) {
        return response()->json(['message' => 'Sesion llena'], 422);
    }

    // comrpobrar si ya tiene reserva en esta sesion
    $existingBooking = Booking::where('user_id', $user->id)
        ->where('session_id', $session->id)
        ->where('status', '!=', 'cancelled')
        ->first();

    if ($existingBooking) {
        return response()->json(['message' => 'Ya tienes reserva en esta sesion'], 422);
    }

    $booking = Booking::create([
        'user_id' => $user->id,
        'session_id' => $session->id,
        'status' => 'confirmed',
    ]);

    $session->increment('current_bookings');

    return response()->json(['data' => $booking], 201);
}
public function destroy(Request $request, $id)
{
    $user = $request->user();
    $booking = Booking::findOrFail($id);

    if ($user->role !== 'admin' && $user->id !== $booking->user_id) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $booking->update([
        'status' => 'cancelled',
        'cancelled_at' => now(),
    ]);

    $booking->session()->decrement('current_bookings');

    return response()->json(['data' => $booking], 200);
}
}
