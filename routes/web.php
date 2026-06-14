<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransWebhookController;

Route::inertia('/', 'welcome')->name('home');

# routes for todo list
Route::get('/todos', [TodoController::class, 'index']);
Route::post('/todos', [TodoController::class, 'store']);
Route::put('/todos/{id}', [TodoController::class, 'update']);
Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
Route::get('/todos/create', [TodoController::class, 'create']);
Route::get('/todos/{id}/edit', [TodoController::class, 'edit']);

# routes for payment
Route::get('/payments/create/{todo}', [PaymentController::class, 'create']) ->name('payments.create');

# webhook for midtrans
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');