<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyecto;
use App\Models\User;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar usuario creador
        $admin = User::firstOrCreate(
            ['correo' => 'admin@sistema.com'],
            [
                'nombre' => 'Administrador del Sistema',
                'clave' => 'admin123',
            ]
        );

        $proyectos = [
            [
                'nombre' => 'Sistema Web E-commerce',
                'fecha_inicio' => '2025-01-15',
                'estado' => 'En Progreso',
                'responsable' => 'José Jara Canales',
                'monto' => 2500000,
                'created_by' => $admin->id
            ],
            [
                'nombre' => 'Migración de Base de Datos Legacy',
                'fecha_inicio' => '2025-02-01',
                'estado' => 'Completado',
                'responsable' => 'Equipo de Desarrollo',
                'monto' => 1800000,
                'created_by' => $admin->id
            ],
            [
                'nombre' => 'Implementación de API REST',
                'fecha_inicio' => '2025-03-10',
                'estado' => 'Pendiente',
                'responsable' => 'José Jara Canales',
                'monto' => 3200000,
                'created_by' => $admin->id
            ],
            [
                'nombre' => 'Desarrollo de Aplicación Móvil',
                'fecha_inicio' => '2025-04-05',
                'estado' => 'En Progreso',
                'responsable' => 'Equipo Móvil',
                'monto' => 4500000,
                'created_by' => $admin->id
            ],
            [
                'nombre' => 'Auditoría de Seguridad IT',
                'fecha_inicio' => '2025-05-20',
                'estado' => 'Cancelado',
                'responsable' => 'Departamento de Seguridad',
                'monto' => 1200000,
                'created_by' => $admin->id
            ]
        ];

        foreach ($proyectos as $proyecto) {
            Proyecto::firstOrCreate(
                [
                    'nombre' => $proyecto['nombre'],
                    'fecha_inicio' => $proyecto['fecha_inicio'],
                ],
                $proyecto
            );
        }
    }
}
