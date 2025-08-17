<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login');

Route::get('/registro', function () {
    return view('auth.registro');
})->name('auth.registro');

// Ruta principal
Route::get('/', function () {
    return redirect()->route('auth.login');
});

// Rutas protegidas de proyectos
Route::middleware('jwt.auth')->group(function () {
    Route::resource('proyectos', ProyectoController::class);
});
