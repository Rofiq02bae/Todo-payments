<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('welcome'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    # routes for todo list
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{id}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');

    # routes for payment
    Route::get('/payments/create/{todo}', [PaymentController::class, 'create'])->name('payments.create');

    # routes for pdf export
    Route::post('/pdf/generate/{todo}', [PdfController::class, 'generate'])->name('pdf.generate');
    Route::get('/pdf/download/{pdfExport}', [PdfController::class, 'download'])->name('pdf.download');
});

# webhook for midtrans (no auth, CSRF excluded in bootstrap/app.php)
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');
