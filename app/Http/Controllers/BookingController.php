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
}