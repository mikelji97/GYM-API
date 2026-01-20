<?php

namespace Database\Seeders;

use App\Models\GymClass;
use Illuminate\Database\Seeder;

class GymClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'Yoga',
                'description' => 'Clase de yoga para mejorar flexibilidad y relajacion',
                'duration' => 60,
                'max_capacity' => 20,
            ],
            [
                'name' => 'Spinning',
                'description' => 'Ciclismo indoor de alta intensidad',
                'duration' => 45,
                'max_capacity' => 25,
            ],
            [
                'name' => 'CrossFit',
                'description' => 'Entrenamiento funcional de alta intensidad',
                'duration' => 60,
                'max_capacity' => 15,
            ],
            [
                'name' => 'Pilates',
                'description' => 'Ejercicios de control corporal y fortalecimiento del core',
                'duration' => 50,
                'max_capacity' => 18,
            ],
            [
                'name' => 'Zumba',
                'description' => 'Baile fitness con musica latina',
                'duration' => 55,
                'max_capacity' => 30,
            ],
            [
                'name' => 'Body Pump',
                'description' => 'Entrenamiento con pesas al ritmo de la musica',
                'duration' => 60,
                'max_capacity' => 25,
            ],
            [
                'name' => 'Boxing',
                'description' => 'Tecnicas de boxeo y cardio intenso',
                'duration' => 45,
                'max_capacity' => 20,
            ],
            [
                'name' => 'Stretching',
                'description' => 'Estiramientos y movilidad articular',
                'duration' => 30,
                'max_capacity' => 25,
            ],
        ];

        foreach ($classes as $class) {
            GymClass::create($class);
        }
    }
}
