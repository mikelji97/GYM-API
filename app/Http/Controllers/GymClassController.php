<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GymClass;

class GymClassController extends Controller
{
    public function index()
    {
        $gymClasses = GymClass::all();

        return response()->json([
            'data' => $gymClasses
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $gymClass = GymClass::create($validated);

        return response()->json([
            'data' => $gymClass
        ], 201);
    }

    public function show(string $id)
    {
        $gymClass = GymClass::findOrFail($id);

        return response()->json([
            'data' => $gymClass
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $gymClass = GymClass::findOrFail($id);
        $gymClass->update($validated);

        return response()->json(['data' => $gymClass], 200);
    }

    public function destroy(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $gymClass = GymClass::findOrFail($id);
        $gymClass->delete();

        return response()->json(['message' => 'Clase eliminada'], 200);
    }
}
