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
        // Solo usuario administrador
        User::create([
            'nombre' => 'Administrador del Sistema',
            'correo' => 'admin@sistema.com',
            'clave' => 'admin123'
        ]);
    }
}
