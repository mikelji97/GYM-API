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
}
