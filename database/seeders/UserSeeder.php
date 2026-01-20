<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gym.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Usuarios normales
        $users = [
            ['name' => 'Juan Garcia', 'email' => 'juan@gmail.com'],
            ['name' => 'Maria Lopez', 'email' => 'maria@gmail.com'],
            ['name' => 'Carlos Martinez', 'email' => 'carlos@gmail.com'],
            ['name' => 'Ana Rodriguez', 'email' => 'ana@gmail.com'],
            ['name' => 'Pedro Sanchez', 'email' => 'pedro@gmail.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }
    }
}
