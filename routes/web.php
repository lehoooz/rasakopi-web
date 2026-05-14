<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::get('/users', function () { return view('admin.dashboard'); });
    Route::get('/reports', function () { return view('admin.dashboard'); });
});

// Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/', function () {
        return view('kasir.dashboard');
    });
    Route::get('/pos', function () {
        return view('kasir.dashboard');
    });
    Route::get('/expenses', function () {
        return view('kasir.dashboard');
    });
    Route::get('/products', function () {
        return view('kasir.dashboard');
    });
});

// Customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/', function () {
        return view('customer.dashboard');
    });
    Route::get('/menu', function () {
        return view('customer.dashboard');
    });
    Route::get('/loyalty', function () {
        return view('customer.dashboard');
    });
});
