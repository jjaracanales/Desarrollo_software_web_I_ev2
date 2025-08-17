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
        // Usuario administrador
        User::create([
            'nombre' => 'Administrador del Sistema',
            'correo' => 'admin@sistema.com',
            'clave' => 'admin123'
        ]);

        // Usuario de ejemplo
        User::create([
            'nombre' => 'José Jara Canales',
            'correo' => 'jose@ejemplo.com',
            'clave' => 'password123'
        ]);

        // Usuario adicional
        User::create([
            'nombre' => 'Usuario Demo',
            'correo' => 'demo@ejemplo.com',
            'clave' => 'demo123'
        ]);
    }
}
