<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $proyectos = Proyecto::orderByDesc('created_at')->limit(5)->get();
            return view('home', compact('proyectos'));
        } catch (Throwable $e) {
            logger()->error('Error de BD en HomeController@index: '.$e->getMessage());
            $proyectos = collect();
            return view('home', [
                'proyectos' => $proyectos,
                'db_error' => 'No se pudo conectar a la base de datos. Revisa las credenciales y el puerto (3307).'
            ]);
        }
    }
}
