<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('gymClass')->get();
        return response()->json(['data' => $sessions], 200);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'gym_class_id' => 'required|exists:gym_classes,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $session = Session::create($validated);

        return response()->json(['data' => $session], 201);
    }

    public function show(string $id)
    {
        $session = Session::findOrFail($id);
        return response()->json(['data' => $session], 200);
    }

    public function update(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $session = Session::findOrFail($id);

        $validated = $request->validate([
            'gym_class_id' => 'required|exists:gym_classes,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $session->update($validated);

        return response()->json(['data' => $session], 200);
    }

    public function destroy(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $session = Session::findOrFail($id);
        $session->delete();

        return response()->json(null, 204);
    }

    public function available()
    {
        $sessions = Session::whereColumn('current_bookings', '<', 'max_capacity')->get();
        return response()->json(['data' => $sessions], 200);
    }
}
