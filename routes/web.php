<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index']);
Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store']);
Route::put('/todos/{id}', [App\Http\Controllers\TodoController::class, 'update']);
Route::delete('/todos/{id}', [App\Http\Controllers\TodoController::class, 'destroy']);
Route::get('/todos/create', [App\Http\Controllers\TodoController::class, 'create']);
Route::get('/todos/{id}/edit', [App\Http\Controllers\TodoController::class, 'edit']);
