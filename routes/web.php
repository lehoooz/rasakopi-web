<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\UserController;

// ── Publik ────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index']);
Route::get('/api/ai-recommendation', [LandingController::class, 'aiRecommendation']);

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    
    // Kelola Produk
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Kelola Akun (Users)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}/edit', [UserController::class, 'edit']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    
    // Laporan
    Route::get('/reports', [ReportController::class, 'index']);
});

// ── Kasir ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/', [OrderController::class, 'history']);
    Route::get('/pos', [OrderController::class, 'pos']);
    Route::post('/pos', [OrderController::class, 'store']);
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);
});

// ── Customer (opsional, hanya kalau login) ────────────────
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'dashboard']);
    Route::get('/menu', [CustomerController::class, 'menu']);
    Route::get('/loyalty', [CustomerController::class, 'loyalty']);
});
