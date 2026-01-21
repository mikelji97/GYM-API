<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();

        if ($loggedUser->role === 'admin' || $loggedUser->id == $id) {
            return response()->json(['data' => $user], 200);
        }

        return response()->json(['message' => 'Acceso denegado'], 403);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();

        if ($loggedUser->role === 'admin' || $loggedUser->id == $id) {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:8'
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $user->update($validated);
            return response()->json(['data' => $user], 200);
        }

        return response()->json(['message' => 'Acceso denegado'], 403);
    }

    public function destroy(Request $request, $id)
    {
        $loggedUser = $request->user();

        if ($loggedUser->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }

    public function stats(Request $request, $id)
    {
        $loggedUser = $request->user();
        $user = User::findOrFail($id);

        if ($loggedUser->role !== 'admin' && $loggedUser->id != $id) {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        if ($loggedUser->role === 'admin' && $loggedUser->id == $id) {
            return response()->json([
                'data' => [
                    'confirmed' => \App\Models\Booking::where('status', 'confirmed')->count(),
                    'cancelled' => \App\Models\Booking::where('status', 'cancelled')->count(),
                    'attended' => \App\Models\Booking::where('status', 'attended')->count(),
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
                'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
                'attended' => $user->bookings()->where('status', 'attended')->count(),
            ]
        ], 200);
    }
}
