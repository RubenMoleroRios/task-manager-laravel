<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('tasks')->group(function (): void {
    Route::get('/', [TaskController::class, 'index']); // Listar todas las tareas
    Route::get('/{id}', [TaskController::class, 'show']); // Lista la tarea por id
    Route::post('/', [TaskController::class, 'store']); // Crea la tarea
    Route::put('/{id}', [TaskController::class, 'update']); // Actualiza la tarea seleccionada
    Route::delete('/{id}', [TaskController::class, 'destroy']); // Borra la tarea seleccionada
});
