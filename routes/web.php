<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('kosan/home');
})->name('home');

Route::get('/kosan', [App\Http\Controllers\KosanController::class, 'index'])->name('kosan.index');

Route::get('login',[App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::get('admin/login',[App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login',[App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.post');

// Routes admin Middleware
Route::middleware('auth.admin')->group(function () {
    Route::get('admin',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/dashboard',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('admin/logout',[App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
    
    // NOTE: Routes untuk CRUD rooms/bookings di-comment karena ini hanya frontend
    // Uncomment dan implementasikan ketika database sudah siap
    // Route::post('admin/rooms',[App\Http\Controllers\AdminController::class, 'storeRoom'])->name('admin.rooms.store');
    // Route::put('admin/rooms/{id}',[App\Http\Controllers\AdminController::class, 'updateRoom'])->name('admin.rooms.update');
    // Route::delete('admin/rooms/{id}',[App\Http\Controllers\AdminController::class, 'deleteRoom'])->name('admin.rooms.delete');
    // Route::post('admin/bookings',[App\Http\Controllers\AdminController::class, 'storeBooking'])->name('admin.bookings.store');
    // Route::put('admin/bookings/{id}',[App\Http\Controllers\AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    // Route::delete('admin/bookings/{id}',[App\Http\Controllers\AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
    // Route::post('admin/settings',[App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
});
