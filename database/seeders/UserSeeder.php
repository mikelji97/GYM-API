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
        User::firstOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Usuarios normales
        $users = [
            ['name' => 'Juan Garcia', 'email' => 'juan@gmail.com'],
            ['name' => 'Maria Lopez', 'email' => 'maria@gmail.com'],
            ['name' => 'Carlos Martinez', 'email' => 'carlos@gmail.com'],
            ['name' => 'Ana Rodriguez', 'email' => 'ana@gmail.com'],
            ['name' => 'Pedro Sanchez', 'email' => 'pedro@gmail.com'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        }
    }
}
