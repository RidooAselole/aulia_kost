<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KosanController;

// Home routes
Route::get('welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('kosan/home');
})->name('home');

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'Test route works!', 'time' => now()]);
});

Route::get('/kosan', [KosanController::class, 'index'])->name('kosan.index');

// ============ ADMIN AUTH ROUTES ============
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// ============ ADMIN PROTECTED ROUTES ============
Route::match(['get', 'post'], 'admin', [AdminController::class, 'dashboard']);
Route::match(['get', 'post'], 'admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

// ============ KOS (ROOMS) ROUTES ============
Route::get('admin/rooms', [KosController::class, 'index'])->name('rooms.index');
Route::prefix('admin/rooms')->group(function () {
    Route::post('/', [KosController::class, 'store'])->name('rooms.store');
    Route::put('/{kos}', [KosController::class, 'update'])->name('rooms.update');
    Route::delete('/{kos}', [KosController::class, 'destroy'])->name('rooms.destroy');
});

// ============ BOOKING ROUTES ============
Route::get('admin/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::prefix('admin/bookings')->group(function () {
    Route::post('/', [BookingController::class, 'store'])->name('bookings.store');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});