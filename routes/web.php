<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskWebController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{id}/toggle', [TaskWebController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('/tasks/{id}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
