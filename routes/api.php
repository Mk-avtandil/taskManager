<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::prefix('tasks')
    ->name('task.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::post('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/save', [TaskController::class, 'save'])->name('save');
        Route::get('/detail/{task}', [TaskController::class, 'detail'])->name('detail');
        Route::get('/edit/{task}', [TaskController::class, 'edit'])->name('edit');
        Route::put('/update/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/delete/{task}', [TaskController::class, 'delete'])->name('delete');
    });

