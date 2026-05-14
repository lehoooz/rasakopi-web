<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Selamat datang, Admin. Ini adalah halaman Dashboard Admin.';
    });
});

// Rute khusus Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir', function () {
        return 'Selamat datang, Kasir. Ini adalah halaman POS / Kasir.';
    });
});

// Rute khusus Customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', function () {
        return 'Selamat datang, Member. Poin Kopi Anda tercatat di sini.';
    });
});