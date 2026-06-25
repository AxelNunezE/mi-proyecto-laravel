<?php

use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\ProveedorApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Productos API
    Route::apiResource('productos', ProductoApiController::class);
    Route::get('productos/buscar', [ProductoApiController::class, 'buscar']);

    // Proveedores API
    Route::apiResource('proveedores', ProveedorApiController::class);
    Route::get('proveedores/buscar', [ProveedorApiController::class, 'buscar']);
});
