<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyecto;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proyectos = [
            [
                'nombre' => 'Sistema Web E-commerce',
                'fecha_inicio' => '2025-01-15',
                'estado' => 'En Progreso',
                'responsable' => 'José Jara Canales',
                'monto' => 2500000,
                'created_by' => 1
            ],
            [
                'nombre' => 'Migración de Base de Datos Legacy',
                'fecha_inicio' => '2025-02-01',
                'estado' => 'Completado',
                'responsable' => 'Equipo de Desarrollo',
                'monto' => 1800000,
                'created_by' => 1
            ],
            [
                'nombre' => 'Implementación de API REST',
                'fecha_inicio' => '2025-03-10',
                'estado' => 'Pendiente',
                'responsable' => 'José Jara Canales',
                'monto' => 3200000,
                'created_by' => 1
            ],
            [
                'nombre' => 'Desarrollo de Aplicación Móvil',
                'fecha_inicio' => '2025-04-05',
                'estado' => 'En Progreso',
                'responsable' => 'Equipo Móvil',
                'monto' => 4500000,
                'created_by' => 1
            ],
            [
                'nombre' => 'Auditoría de Seguridad IT',
                'fecha_inicio' => '2025-05-20',
                'estado' => 'Cancelado',
                'responsable' => 'Departamento de Seguridad',
                'monto' => 1200000,
                'created_by' => 1
            ]
        ];

        foreach ($proyectos as $proyecto) {
            Proyecto::create($proyecto);
        }
    }
}
