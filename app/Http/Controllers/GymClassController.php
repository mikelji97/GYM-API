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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gymClass = GymClass::findOrFail($id);

        return response()->json([
            'data' => $gymClass
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gymClass = GymClass::findOrFail($id);
        $gymClass->delete();

        return response()->json(['message' => 'Gym class deleted successfully'], 200);
    }
}
