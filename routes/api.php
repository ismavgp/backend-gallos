<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GalloController;
use App\Http\Controllers\Api\VacunaController;
use App\Http\Controllers\Api\PeleaController;
use App\Http\Controllers\Api\EntrenamientoController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ReportesController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Subida de archivos
    Route::post('/files/upload-image', [FileController::class, 'uploadImage']);
    Route::post('/files/upload-multiple', [FileController::class, 'uploadMultiple']);
    Route::delete('/files/delete-image', [FileController::class, 'deleteImage']);

    // CRUD de Gallos
    Route::apiResource('gallos', GalloController::class);
    Route::get('gallos/{gallo}/estadisticas', [GalloController::class, 'estadisticas']);

    // CRUD de Vacunas
    Route::apiResource('vacunas', VacunaController::class);

    // CRUD de Peleas
    Route::apiResource('peleas', PeleaController::class);
    Route::patch('peleas/{pelea}/resultado', [PeleaController::class, 'actualizarResultado']);

    // CRUD de Entrenamientos
    Route::apiResource('entrenamientos', EntrenamientoController::class);
    Route::get('entrenamientos/gallo/{galloId}/resumen', [EntrenamientoController::class, 'resumenPorGallo']);

    // Reportes
    Route::post('/reportes/ficha-tecnica', [ReportesController::class, 'fichaTecnicaGallo']);
    Route::post('/reportes/rendimiento', [ReportesController::class, 'reporteRendimiento']);
});
