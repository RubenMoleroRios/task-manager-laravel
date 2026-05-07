<?php

use App\Http\Controllers\TaskWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskWebController::class, 'update'])->name('tasks.update');
Route::patch('/tasks/{id}/toggle', [TaskWebController::class, 'toggle'])->name('tasks.toggle');
Route::delete('/tasks/{id}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');
