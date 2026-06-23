<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\PdfController;

Route::inertia('/', 'welcome')->name('home');

# routes for todo list
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
Route::put('/todos/{id}', [TodoController::class, 'update'])->name('todos.update');
Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');
Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');

# routes for payment
Route::get('/payments/create/{todo}', [PaymentController::class, 'create']) ->name('payments.create');

# routes for pdf export
Route::post('/pdf/generate/{todo}', [PdfController::class, 'generate'])->name('pdf.generate');
Route::get('/pdf/download/{pdfExport}', [PdfController::class, 'download'])->name('pdf.download');

# webhook for midtrans
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');
