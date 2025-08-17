<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Log;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Diagnóstico: confirmar que la petición llega al controlador
        Log::info('POST /proyectos (store) recibido', [
            'path' => $request->path(),
            'method' => $request->method(),
            'user_id' => $request->user_id ?? null,
        ]);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:100',
            'responsable' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0'
        ]);

        // Validar que venga un usuario autenticado por JWT
        if (!$request->user_id) {
            Log::warning('POST /proyectos sin user_id en request (JWT ausente)');
            return redirect()->route('auth.login')->with('error', 'Tu sesión no es válida o expiró. Inicia sesión nuevamente.');
        }

        // Preparar datos y forzar created_by desde JWT
        $data = $request->only(['nombre', 'fecha_inicio', 'estado', 'responsable', 'monto']);
        $data['created_by'] = $request->user_id;

        try {
            $proyecto = Proyecto::create($data);
            Log::info('Proyecto creado OK', ['id' => $proyecto->id]);
            return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente');
        } catch (\Throwable $e) {
            Log::error('Error al crear proyecto', ['exception' => $e->getMessage()]);
            return back()->withInput()->with('error', 'No se pudo crear el proyecto: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.edit', compact('proyecto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:100',
            'responsable' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0'
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->all());
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado exitosamente');
    }
}
