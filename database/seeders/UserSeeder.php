<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o reutilizar usuario administrador (idempotente)
        User::firstOrCreate(
            ['correo' => 'admin@sistema.com'],
            [
                'nombre' => 'Administrador del Sistema',
                'clave' => 'admin123', // se hashea en el mutator del modelo
            ]
        );
    }
}
