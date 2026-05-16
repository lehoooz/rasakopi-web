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
use App\Http\Controllers\QrOrderController;

// ── Publik ────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index']);

// ── QR Dine In (Guest & Customer) ─────────────────────────
Route::get('/qr-menu', [QrOrderController::class, 'index']);
Route::post('/qr-menu/cart/add', [QrOrderController::class, 'addToCart']);
Route::post('/qr-menu/cart/remove', [QrOrderController::class, 'removeFromCart']);
Route::get('/qr-menu/checkout', [QrOrderController::class, 'checkout']);
Route::post('/qr-menu/checkout', [QrOrderController::class, 'processCheckout']);
Route::get('/qr-menu/success', [QrOrderController::class, 'success']);

// Auth Customer
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Auth Staff
Route::middleware('guest')->group(function () {
    Route::get('/staff/login', [AuthController::class, 'showStaffLogin'])->name('staff.login');
    Route::post('/staff/login', [AuthController::class, 'staffLogin']);
});
Route::post('/staff/logout', [AuthController::class, 'staffLogout'])->name('staff.logout');

use App\Http\Controllers\AdminTableController;

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
    
    // Kelola Meja (QR)
    Route::get('/tables', [AdminTableController::class, 'index']);
    Route::post('/tables', [AdminTableController::class, 'store']);
    Route::delete('/tables/{table}', [AdminTableController::class, 'destroy']);
    Route::get('/tables/print', [AdminTableController::class, 'print']);
    
    // Kelola Akun (Users)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}/edit', [UserController::class, 'edit']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    
    // Laporan
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/export', [ReportController::class, 'export']);
});

// ── Kasir ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/', [OrderController::class, 'history']);
    Route::get('/pos', [OrderController::class, 'pos']);
    Route::post('/pos', [OrderController::class, 'store']);
    Route::post('/orders/{order}/complete', [OrderController::class, 'completeQrOrder']);
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
