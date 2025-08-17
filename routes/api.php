<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas de autenticación
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas que requieren autenticación
Route::middleware('jwt.auth')->group(function () {
    // Rutas de proyectos
    Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show']);
    Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{proyecto}', [ProyectoController::class, 'destroy']);
    
    // Ruta para obtener información del usuario autenticado
    Route::get('/usuario', function (Request $request) {
        return response()->json([
            'success' => true,
            'user_id' => $request->user_id,
            'user_email' => $request->user_email
        ]);
    });
});
