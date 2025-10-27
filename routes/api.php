<?php

use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CanjeController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas API de la aplicación. Todas estas rutas
| son flexibles y funcionan en paralelo con la interfaz web.
| Sistema de autenticación: Laravel Sanctum (tokens)
|
*/

// ============================================================
// Rutas Públicas (sin autenticación)
// ============================================================

// Autenticación
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ============================================================
// Rutas Protegidas (requieren token de autenticación)
// ============================================================

Route::middleware('auth:sanctum')->group(function () {

    // Autenticación
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/revoke-all', [AuthController::class, 'revokeAll']);
    });

    // Materiales Reciclables
    Route::apiResource('materiales', MaterialController::class);

    // Útiles Escolares (Productos)
    Route::apiResource('productos', ProductoController::class);

    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);

    // Alumnos (solo profesores)
    Route::post('/profesores/alumnos', [AlumnoController::class, 'store']);
    Route::post('/profesores/alumnos/{id}/asignar-recipuntos', [AlumnoController::class, 'asignarRecipuntos']);
    Route::post('/profesores/alumnos/{id}/registrar-examen', [AlumnoController::class, 'registrarExamen']);

    // Canjes (Profesor, Admin y Alumno)
    Route::get('/canjes', [CanjeController::class, 'index']);
    Route::post('/canjes', [CanjeController::class, 'store']);
    Route::get('/canjes/stats', [CanjeController::class, 'stats']);
    Route::get('/canjes/{id}', [CanjeController::class, 'show']);
    Route::delete('/canjes/{id}', [CanjeController::class, 'destroy']);
});
