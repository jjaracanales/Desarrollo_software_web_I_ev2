<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login');

Route::get('/registro', function () {
    return view('auth.registro');
})->name('auth.registro');

// Home público
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de proyectos
// Públicas (solo lectura)
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');

// Protegidas (cambios requieren login)
Route::middleware('jwt.auth')->group(function () {
    Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
    Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{proyecto}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');
});

// Mostrar detalle (colocada al final y limitada a numérico para no capturar 
// accidentalmente rutas como "/proyectos/create")
Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show'])
    ->whereNumber('proyecto')
    ->name('proyectos.show');
